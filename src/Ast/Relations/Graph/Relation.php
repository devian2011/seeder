<?php

namespace Devian2011\Seeder\Ast\Relations\Graph;

use Devian2011\Seeder\Configuration\RelativeColumn;

class Relation
{
    /** @var string */
    private string $id;
    /** @var Node */
    private Node $parent;
    /** @var Node */
    private Node $child;
    /** @var RelativeColumn */
    private RelativeColumn $relativeColumn;

    public function __construct(Node $parent, Node $child, RelativeColumn $relativeColumn)
    {
        $this->parent = $parent;
        $this->child = $child;
        $this->relativeColumn = $relativeColumn;
        $this->generateId();
    }

    private function generateId()
    {
        $this->id = sprintf("%s-%s", $this->parent->getId(), $this->child->getId());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Node
     */
    public function getParent(): Node
    {
        return $this->parent;
    }

    /**
     * @param Node $parent
     */
    public function setParent(Node $parent): void
    {
        $this->parent = $parent;
        $this->generateId();
    }

    /**
     * @return Node
     */
    public function getChild(): Node
    {
        return $this->child;
    }

    /**
     * @param Node $child
     */
    public function setChild(Node $child): void
    {
        $this->child = $child;
        $this->generateId();
    }

    /**
     * @return RelativeColumn
     */
    public function getRelativeColumn(): RelativeColumn
    {
        return $this->relativeColumn;
    }

    /**
     * @param RelativeColumn $relativeColumn
     */
    public function setRelativeColumn(RelativeColumn $relativeColumn): void
    {
        $this->relativeColumn = $relativeColumn;
    }
}
