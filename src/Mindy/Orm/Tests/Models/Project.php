<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * Author: Maxim Falaleev <max@studio107.ru>
 */

namespace Mindy\Orm\Tests\Models;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\ManyToManyField;
use Mindy\Orm\Model;

/**
 * Class Project.
 *
 * @property string name
 * @property \Mindy\Orm\ManyToManyManager workers
 */
class Project extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
            ],
            'workers' => [
                'class' => ManyToManyField::class,
                'modelClass' => Worker::class,
                'through' => ProjectMembership::class,
                'link' => ['project_id', 'worker_id'],
            ],
        ];
    }
}