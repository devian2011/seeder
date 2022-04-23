<?php

namespace Devian2011\Seeder\Ast;

use Devian2011\Seeder\Ast\Columns\ColumnValueRegistryFactoryInterface;
use Devian2011\Seeder\Ast\Columns\ColumnValueResolverFactoryInterface;
use Devian2011\Seeder\Configuration\Column;
use Devian2011\Seeder\Configuration\Table;
use Faker\Generator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class TableColumnsResolver
{
    private Generator $faker;
    private ExpressionLanguage $expressions;
    private ColumnValueRegistryFactoryInterface $columnValueRegistryFactory;
    private ColumnValueResolverFactoryInterface $columnValueResolverFactory;

    public function __construct(
        Generator                           $generator,
        ExpressionLanguage                  $expressionLanguage,
        ColumnValueRegistryFactoryInterface $columnValueRegistryFactory,
        ColumnValueResolverFactoryInterface $columnValueResolverFactory
    )
    {
        $this->faker = $generator;
        $this->expressions = $expressionLanguage;
        $this->columnValueRegistryFactory = $columnValueRegistryFactory;
        $this->columnValueResolverFactory = $columnValueResolverFactory;
    }

    /**
     * @param Column[] $columns
     * @return array
     */
    private function resolveColumns(array $columns): array
    {
        $registry = $this->columnValueRegistryFactory->getRegistry();
        foreach ($columns as $column) {
            if ($column->getValue() === 'auto_increment') {
                continue;
            }
            if (!$registry->isValueExists($column->getName())) {
                $columnValueResolver = $this->columnValueResolverFactory
                    ->getColumnValueResolver(
                        $column,
                        $columns,
                        $registry,
                        $this->expressions,
                        $this->faker
                    );
                $registry->setColumnValue($column->getName(), $columnValueResolver->resolve());
            }
        }

        return $registry->asArray();
    }

    /**
     * @param Table $table
     * @return array
     */
    public function generate(Table $table): array
    {
        $result = [];
        for ($row = 0; $row < $table->getRowQuantity(); $row++) {
            $columns = $this->resolveColumns($table->getColumns());
            $result[$row] = $columns;
        }

        return $result;
    }
}

