<?php

namespace Devian2011\Seeder\Configuration;

class RelativeColumn
{
    private const RELATION_ONE_TO_ONE = 'oneToOne';
    private const RELATION_MANY_TO_ONE = 'manyToOne';
    private const MANY_TO_MANY = 'manyToMany';

    private string $name;
    private string $database;
    private string $table;
    private string $column;
    private string $type;
    private ?string $throughTable;

    /**
     * @param string $name
     * @param string $database
     * @param string $table
     * @param string $column
     * @param string $type
     * @param ?string $throughTable
     */
    public function __construct(string $name, string $database, string $table, string $column, string $type, ?string $throughTable)
    {
        $this->name = $name;
        $this->database = $database;
        $this->table = $table;
        $this->column = $column;
        $this->type = $type;
        $this->throughTable = $throughTable;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getThroughTable(): ?string
    {
        return $this->throughTable;
    }
}
