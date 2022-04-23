<?php

namespace Devian2011\Seeder\Ast\Columns;

class ColumnValueRegistry implements ColumnValueRegistryInterface
{
    /**
     * @var array
     */
    private array $columnsValues = [];

    /**
     * @param string $columnName
     * @return mixed
     * @throws ColumnValueResolverException
     */
    public function getValue(string $columnName)
    {
        if (!$this->isValueExists($columnName)) {
            throw new ColumnValueResolverException("Try to take value for column: {$columnName} which is not exists");
        }
        return $this->columnsValues[$columnName];
    }

    public function isValueExists(string $columnName): bool
    {
        return isset($this->columnsValues[$columnName]);
    }

    public function setColumnValue(string $columnName, $value): void
    {
        $this->columnsValues[$columnName] = $value;
    }

    public function asArray(): array
    {
        return $this->columnsValues;
    }
}
