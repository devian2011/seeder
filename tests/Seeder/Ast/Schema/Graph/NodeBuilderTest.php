<?php

namespace Devian2011\Seeder\Tests\Ast\Schema\Graph;

use Devian2011\Seeder\Ast\Schema\Graph\Graph;
use Devian2011\Seeder\Ast\Schema\Graph\NodeBuilder;
use Devian2011\Seeder\Configuration\RelativeColumn;
use Devian2011\Seeder\Configuration\Table;
use PHPUnit\Framework\TestCase;

class NodeBuilderTest extends TestCase
{

    public function testBuild()
    {
        $tables = [
            'admin.users' => new Table('admin', 'users', [], [], [
                new RelativeColumn(
                    'role_id',
                    'admin',
                    'user_roles',
                    'id',
                    RelativeColumn::RELATION_MANY_TO_ONE,
                    null
                ),
                new RelativeColumn(
                    'info_id',
                    'info',
                    'user_info',
                    'id',
                    RelativeColumn::RELATION_ONE_TO_ONE,
                    null
                ),
            ], 1, [], 'id'),
            'admin.user_roles' => new Table('admin', 'user_roles', [], [], [], 1, [], 'id'),
            'info.user_info' => new Table('info', 'user_info', [], [], [], 1, [], 'id')
        ];

        $nodeBuilder = new NodeBuilder();
        $graph = new Graph();
        $result = $nodeBuilder->build($tables['admin.users'], $tables, $graph);

        $this->assertEquals($tables['admin.users'], $result->getTable());
        $this->assertEquals($tables['admin.users']->getId(), $result->getId());
        $relations = $result->getRelations();

        $this->assertEquals('admin.user_roles-admin.users', $relations['admin.user_roles-admin.users']->getId());
        $this->assertEquals('admin.user_roles', $relations['admin.user_roles-admin.users']->getParent()->getTable()->getId());
        $this->assertEquals('admin.users', $relations['admin.user_roles-admin.users']->getChild()->getTable()->getId());
        $this->assertEquals('user_roles', $relations['admin.user_roles-admin.users']->getRelativeColumn()->getTable());
        $this->assertEquals('id', $relations['admin.user_roles-admin.users']->getRelativeColumn()->getColumn());
        $this->assertEquals('role_id', $relations['admin.user_roles-admin.users']->getRelativeColumn()->getName());
        $this->assertEquals('admin', $relations['admin.user_roles-admin.users']->getRelativeColumn()->getDatabase());
        $this->assertEquals(RelativeColumn::RELATION_MANY_TO_ONE, $relations['admin.user_roles-admin.users']->getRelativeColumn()->getType());
        $this->assertNull($relations['admin.user_roles-admin.users']->getRelativeColumn()->getThroughTable());

        $this->assertEquals('info.user_info-admin.users',$relations['info.user_info-admin.users']->getId());
        $this->assertEquals('info.user_info', $relations['info.user_info-admin.users']->getParent()->getTable()->getId());
        $this->assertEquals('admin.users', $relations['info.user_info-admin.users']->getChild()->getTable()->getId());
        $this->assertEquals('user_info', $relations['info.user_info-admin.users']->getRelativeColumn()->getTable());
        $this->assertEquals('id', $relations['info.user_info-admin.users']->getRelativeColumn()->getColumn());
        $this->assertEquals('info_id', $relations['info.user_info-admin.users']->getRelativeColumn()->getName());
        $this->assertEquals('info', $relations['info.user_info-admin.users']->getRelativeColumn()->getDatabase());
        $this->assertEquals(RelativeColumn::RELATION_ONE_TO_ONE, $relations['info.user_info-admin.users']->getRelativeColumn()->getType());
        $this->assertNull($relations['info.user_info-admin.users']->getRelativeColumn()->getThroughTable());

    }

}
