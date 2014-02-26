<?php
/**
 * 
 *
 * All rights reserved.
 * 
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 04/01/14.01.2014 01:15
 */

namespace Tests\Orm;


use Tests\Models\ForeignKeyModel;
use Tests\DatabaseTestCase;

class RelationTest extends DatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->initModels([new ForeignKeyModel]);
    }

    public function tearDown()
    {
        $this->dropModels([new ForeignKeyModel]);
    }

    public function testInit()
    {
        $model = new ForeignKeyModel();
        $schema = $model->getTableSchema();
        $this->assertTrue(isset($schema->columns['id']));
        $this->assertTrue(isset($schema->columns['something_id']));
    }

    public function testForeignKey()
    {
        $model = new ForeignKeyModel();
        $fk = $model->getField('something');
        $this->assertInstanceOf($model->foreignField, $fk);
        $this->assertNull($fk->getValue());
    }
}