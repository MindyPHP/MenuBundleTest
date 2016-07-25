<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/06/16
 * Time: 11:59
 */

namespace Mindy\QueryBuilder\Q;

use Exception;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Interfaces\IAdapter;
use Mindy\QueryBuilder\Interfaces\ILookupBuilder;
use Mindy\QueryBuilder\QueryBuilder;

abstract class Q
{
    /**
     * @var array|string|Q
     */
    protected $where;
    /**
     * @var string
     */
    protected $operator;
    /**
     * @var ILookupBuilder
     */
    protected $lookupBuilder;
    /**
     * @var IAdapter
     */
    protected $adapter;

    public function __construct($where)
    {
        $this->where = $where;
    }

    public function setLookupBuilder(ILookupBuilder $lookupBuilder)
    {
        $this->lookupBuilder = $lookupBuilder;
        return $this;
    }

    public function setAdapter(IAdapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function addWhere($where)
    {
        $this->where[] = $where;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return string
     */
    public function toSQL()
    {
        return $this->parseWhere();
    }

    /**
     * @return string
     */
    protected function parseWhere()
    {
        return $this->parseConditions($this->where);
    }

    private function isWherePart($where)
    {
        return is_array($where) &&
        array_key_exists('___operator', $where) &&
        array_key_exists('___where', $where) &&
        array_key_exists('___condition', $where);
    }

    /**
     * @param array $where
     * @return string
     */
    protected function parseConditions($where)
    {
        if (empty($where)) {
            return '';
        }

        $sql = '';
        if ($this->isWherePart($where)) {
            $operator = $where['___operator'];
            $childWhere = $where['___where'];
            $condition = $where['___condition'];
            if ($this->isWherePart($childWhere)) {
                $whereSql = $this->parseConditions($childWhere);
                $sql .= '(' . $whereSql . ') ' . strtoupper($operator) . ' (' . $this->parsePart($condition) . ')';
            } else {
                $sql .= $this->parsePart($childWhere);
            }
        } else {
            $sql .= $this->parsePart($where);
        }

        if (empty($sql)) {
            return '';
        }

        return $sql;
    }

    /**
     * @param $part
     * @return string
     * @throws Exception
     */
    protected function parsePart($part)
    {
        if (is_string($part)) {
            return $part;
        } else if (is_array($part)) {
            $sql = [];
            foreach ($part as $key => $value) {
                if ($value instanceof Q) {
                    $sql[] = '(' . $this->parsePart($value) . ')';
                } else if (is_numeric($key) && is_array($value)) {
                    $sql[] = $this->parsePart($value);
                } else {
                    list($lookup, $column, $lookupValue) = $this->lookupBuilder->parseLookup($key, $value);
                    $sql[] = $this->adapter->runLookup($lookup, $column, $lookupValue);
                }
            }
            return implode(' ' . $this->getOperator() . ' ', $sql);
        } else if ($part instanceof Q) {
            $part->setLookupBuilder($this->lookupBuilder);
            $part->setAdapter($this->adapter);
            return $part->toSQL();
        } else if ($part instanceof QueryBuilder) {
            return $part->toSQL();
        } else {
            throw new Exception("Unknown sql part type");
        }
    }
}