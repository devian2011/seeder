<?php

namespace Devian2011\Seeder\Ast\Schema\Graph;

use Devian2011\Seeder\Configuration\Table;

class Node
{
    /** @var Relation[] */
    private array $relations;
    /** @var Table */
    private Table $table;
    /** @var array */
    private array $children = [];

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->table->getId();
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    public function addRelation(Relation $relation)
    {
        $this->relations[$relation->getId()] = $relation;
    }

    public function rmRelation(Relation $relation)
    {
        unset($this->relations[$relation->getId()]);
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

    public function hasRelations(): bool
    {
        return !empty($this->relations);
    }

    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(string $childNodeName)
    {
        $this->children[] = $childNodeName;
    }
}
