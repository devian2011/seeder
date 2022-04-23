<?php

namespace Devian2011\Seeder\Configuration;

class Root
{
    /** @var Database[]  */
    private array $databases = [];
    /** @var Table[] */
    private array $tables;

    /**
     * @param Database[] $databases
     * @param Table[] $tables
     */
    public function __construct(array $databases, array $tables)
    {
        $this->databases = $databases;
        $this->tables = $tables;
    }

    /**
     * @return Database[]
     */
    public function getDatabases(): array
    {
        return $this->databases;
    }

    /**
     * @return Table[]
     */
    public function getTables(): array
    {
        return $this->tables;
    }
}
