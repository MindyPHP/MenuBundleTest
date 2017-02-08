<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * Author: Maxim Falaleev <max@studio107.ru>
 */

namespace Mindy\Bundle\OrmBundle\Command;

use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand as BaseExecuteCommand;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ExecuteCommand extends BaseExecuteCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use MigrationConfigurationTrait;
}