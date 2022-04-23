<?php

namespace Devian2011\Seeder\Configuration;

class Table
{
    public const TABLE_MODE_PREDEFINED = 'predefined';
    public const TABLE_MODE_TEST = 'test';
    public const TABLE_MODE_FAKE = 'fake';

    /** @var string */
    private string $database;
    /** @var string */
    private string $name;
    /** @var string[] */
    private array $mods;
    /** @var Column[] */
    private array $columns;
    /** @var RelativeColumn[] */
    private array $relations;
    /** @var string */
    private string $rowQuantity;
    /** @var string|array */
    private $primaryKey;
    /** @var array */
    private $fixed = [];

    /**
     * @param string $database
     * @param string $name
     * @param array $mods
     * @param Column[] $columns
     * @param RelativeColumn[] $relations
     * @param string $rowQuantity
     * @param Column[] $fixed
     * @param string|array $primaryKey
     */
    public function __construct(
        string $database,
        string $name,
        array  $mods,
        array  $columns,
        array  $relations,
        string $rowQuantity,
        array  $fixed = [],
               $primaryKey = 'id'
    )
    {
        $this->database = $database;
        $this->name = $name;
        $this->mods = $mods;
        foreach ($columns as $column) {
            $this->columns[$column->getName()] = $column;
        }
        foreach ($relations as $relation) {
            $this->relations[$relation->getName()] = $relation;
        }
        $this->rowQuantity = $rowQuantity;
        $this->primaryKey = $primaryKey;
        $this->fixed = $fixed;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getMods(): array
    {
        return $this->mods;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return RelativeColumn[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @return array|string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return string
     */
    public function getRowQuantity(): string
    {
        return $this->rowQuantity;
    }

    /**
     * @return Column[]
     */
    public function getFixed(): array
    {
        return $this->fixed;
    }
}
