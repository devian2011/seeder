<?php

namespace Devian2011\Seeder\Tests\Loader;

use Devian2011\Seeder\Ast\Schema\Graph\Graph;
use Devian2011\Seeder\Ast\Schema\Graph\Node;
use Devian2011\Seeder\Configuration\Table;
use Devian2011\Seeder\Db\DBConnectionInterface;
use Devian2011\Seeder\Db\DBConnectionPool;
use Devian2011\Seeder\Db\DBConnectionPoolInterface;
use Devian2011\Seeder\Loader\TableEraser;
use PHPUnit\Framework\TestCase;

class TableEraserTest extends TestCase
{

    public function testErase()
    {
        $nodeParent = new Node(new Table('admin', 'first', [], [], [], 0, [], 'id'));
        $nodeChild1 = new Node(new Table('admin', 'child1', [], [], [], 0, [], 'id'));
        $nodeChild2 = new Node(new Table('admin', 'child2', [], [], [], 0, [], 'id'));

        $nodeParent->addChild($nodeChild1->getId());
        $nodeChild1->addChild($nodeChild2->getId());

        $graph = new Graph();
        $graph->setNodes([$nodeParent, $nodeChild1, $nodeChild2]);

        $connParent = $this->createMock(\PDO::class);
        $connParent->expects($this->exactly(3))->method('query');
        $dbParent = $this->createMock(DBConnectionInterface::class);
        $dbParent->method('getConn')->willReturn($connParent);

        $connPool = $this->createMock(DBConnectionPoolInterface::class);
        $connPool->method('getDb')
            ->with($this->equalTo('admin'))
            ->willReturn($dbParent);

        $eraser = new TableEraser($connPool);
        $eraser->erase($nodeParent, $graph);

    }

}
