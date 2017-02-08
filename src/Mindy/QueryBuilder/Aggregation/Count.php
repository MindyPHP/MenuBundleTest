<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * Author: Maxim Falaleev <max@studio107.ru>
 */

namespace Mindy\QueryBuilder\Aggregation;

class Count extends Aggregation
{
    public function toSQL()
    {
        return 'COUNT('.parent::toSQL().')'.(empty($this->alias) ? '' : ' AS [['.$this->alias.']]');
    }
}