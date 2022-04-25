<?php

namespace Devian2011\Seeder\Ast\Schema\Graph;

use Devian2011\Seeder\Configuration\Table;

class GraphBuilder
{
    /** @var Table[] */
    private array $tables = [];
    /** @var NodeBuilderInterface */
    private NodeBuilderInterface $nodeBuilder;
    /** @var ?Graph */
    private ?Graph $graph = null;

    /**
     * @param Table[] $tables
     */
    public function __construct(array $tables, NodeBuilderInterface $nodeBuilder)
    {
        foreach ($tables as $table) {
            $this->tables[$table->getId()] = $table;
        }
        $this->nodeBuilder = $nodeBuilder;
    }

    private function build()
    {
        $this->graph = new Graph();
        foreach ($this->tables as $table) {
            //TODO: Optimize graph builder
            $this->graph->addNode($this->nodeBuilder->build($table, $this->tables, $this->graph));
        }
    }

    public function getGraph(): Graph
    {
        if (empty($this->graph)) {
            $this->build();
        }
        return $this->graph;
    }
}
