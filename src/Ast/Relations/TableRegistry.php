<?php

namespace Devian2011\Seeder\Ast\Relations;

use Devian2011\Seeder\Configuration\Table;

class TableRegistry
{
    private array $tables = [];

    /**
     * @param Table $table
     * @return Table
     * @throws TableResolverException
     */
    public function getTable(Table $table): Table
    {
        return $this->getTableByRelation($table->getDatabase(), $table->getName());
    }

    public function isTableExists(Table $table): bool
    {
        return $this->isTableExistsByRelation($table->getDatabase(), $table->getName());
    }

    public function isTableExistsByRelation(string $database, string $name): bool
    {
        return isset($this->tables[sprintf('%s.%s', $database, $name)]);
    }

    /**
     * @param string $database
     * @param string $name
     * @return Table
     * @throws TableResolverException
     */
    public function getTableByRelation(string $database, string $name): Table
    {
        if ($this->isTableExistsByRelation($database, $name)) {
            return $this->tables[sprintf('%s.%s', $database, $name)];
        }
        throw new TableResolverException("Unknown table db: {$database} name: {$name}");
    }

    public function setTable(Table $table)
    {
        $this->tables[sprintf('%s.%s', $table->getDatabase(), $table->getName())] = $table;
    }

}
