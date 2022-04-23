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

    /**
     * @param string $database
     * @param string $name
     * @param array $mods
     * @param array $columns
     * @param array $relations
     * @param string $rowQuantity
     * @param string|array $primaryKey
     */
    public function __construct(
        string $database,
        string $name,
        array  $mods,
        array  $columns,
        array  $relations,
        string $rowQuantity,
               $primaryKey = 'id'
    )
    {
        $this->database = $database;
        $this->name = $name;
        $this->mods = $mods;
        $this->columns = $columns;
        $this->relations = $relations;
        $this->rowQuantity = $rowQuantity;
        $this->primaryKey = $primaryKey;
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
}
