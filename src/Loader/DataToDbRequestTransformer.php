<?php

namespace Devian2011\Seeder\Loader;

use Devian2011\Seeder\Configuration\Table;

class DataToDbRequestTransformer
{
    public function transformDataToSql(Table $table, array $data): array
    {
        $result = [];
        foreach ($data as $row) {
            $columnNames = array_keys($row);
            $columnPlaceholders = array_map(function ($column) {
                return ":{$column}";
            }, $columnNames);
            $result[] = [
                'sql' => "INSERT INTO {$table->getName()} (" . implode(',', $columnNames) . ") VALUES (" . implode(',', $columnPlaceholders) . ")",
                'data' => array_combine($columnPlaceholders, array_values($row))
            ];
        }

        return $result;
    }
}
