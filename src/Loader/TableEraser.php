<?php

namespace Devian2011\Seeder\Loader;

use Devian2011\Seeder\Ast\Schema\Graph\Graph;
use Devian2011\Seeder\Ast\Schema\Graph\Node;
use Devian2011\Seeder\Db\DBConnectionPoolInterface;

class TableEraser
{
    private DBConnectionPoolInterface $connectionPool;

    public function __construct(DBConnectionPoolInterface $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    public function erase(Node $node, Graph $schema)
    {
        if ($node->hasChildren()) {
            foreach ($node->getChildren() as $child) {
                $this->erase($schema->getNode($child), $schema);
            }
        }
        $conn = $this->connectionPool->getDb($node->getTable()->getDatabase());
        $conn->getConn()->query("TRUNCATE {$node->getTable()->getName()}");
    }
}
