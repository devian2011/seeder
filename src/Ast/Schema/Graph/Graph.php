<?php

namespace Devian2011\Seeder\Ast\Schema\Graph;

class Graph
{
    /** @var Node[] */
    private array $nodes = [];

    public function setNodes(array $nodes)
    {
        foreach ($nodes as $node) {
            $this->nodes[$node->getId()] = $node;
        }
    }

    public function addNode(Node $node)
    {
        //TODO: May be throw exception that node with same id is already exists
        $this->nodes[$node->getId()] = $node;
    }

    public function hasNode(string $nodeId): bool
    {
        return isset($this->nodes[$nodeId]);
    }

    public function getNode(string $nodeId): Node
    {
        return $this->nodes[$nodeId];
    }

    /**
     * @return Node[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

}
