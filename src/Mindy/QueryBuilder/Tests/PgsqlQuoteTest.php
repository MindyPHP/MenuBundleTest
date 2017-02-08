<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * Author: Maxim Falaleev <max@studio107.ru>
 */

namespace Mindy\QueryBuilder\Tests;

class PgsqlQuoteTest extends BaseTest
{
    protected $driver = 'pgsql';

    public function testAutoQuoting()
    {
        $sql = 'SELECT [[id]], [[t.name]] FROM {{customer}} t';
        $this->assertEquals('SELECT "id", "t"."name" FROM "customer" t', $this->getAdapter()->quoteSql($sql));
    }

    public function testQuoteValue()
    {
        $adapter = $this->getAdapter();
        $this->assertEquals(123, $adapter->quoteValue(123));
        $this->assertEquals("'string'", $adapter->quoteValue('string'));
        $this->assertEquals("'It''s interesting'", $adapter->quoteValue("It's interesting"));
    }

    public function testQuoteTableName()
    {
        $adapter = $this->getAdapter();
        $this->assertEquals('"table"', $adapter->quoteTableName('table'));
        $this->assertEquals('"table"', $adapter->quoteTableName('"table"'));
        $this->assertEquals('"schema"."table"', $adapter->quoteTableName('schema.table'));
        $this->assertEquals('"schema"."table"', $adapter->quoteTableName('schema."table"'));
        $this->assertEquals('"schema"."table"', $adapter->quoteTableName('"schema"."table"'));
        $this->assertEquals('{{table}}', $adapter->quoteTableName('{{table}}'));
        $this->assertEquals('(table)', $adapter->quoteTableName('(table)'));
    }

    public function testQuoteColumnName()
    {
        $adapter = $this->getAdapter();
        $this->assertEquals('"column"', $adapter->quoteColumn('column'));
        $this->assertEquals('"column"', $adapter->quoteColumn('"column"'));
        $this->assertEquals('"table"."column"', $adapter->quoteColumn('table.column'));
        $this->assertEquals('"table"."column"', $adapter->quoteColumn('table."column"'));
        $this->assertEquals('"table"."column"', $adapter->quoteColumn('"table"."column"'));
        $this->assertEquals('[[column]]', $adapter->quoteColumn('[[column]]'));
        $this->assertEquals('{{column}}', $adapter->quoteColumn('{{column}}'));
        $this->assertEquals('(column)', $adapter->quoteColumn('(column)'));
    }
}