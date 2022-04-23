<?php

namespace Devian2011\Seeder\Ast\Columns;

use Devian2011\Seeder\Configuration\Column;

interface ColumnValueRegistryInterface
{
    /**
     * @param string $columnName
     * @return mixed
     * @throws ColumnValueResolverException
     */
    public function getValue(string $columnName);

    public function isValueExists(string $columnName): bool;

    public function setColumnValue(string $columnName, $value): void;

    public function asArray(): array;
}
