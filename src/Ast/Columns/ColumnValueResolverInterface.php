<?php

namespace Devian2011\Seeder\Ast\Columns;

use Devian2011\Seeder\Configuration\Column;
use Faker\Generator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

interface ColumnValueResolverInterface
{
    public function __construct(
        ColumnValueResolverFactoryInterface $columnValueResolverFactory,
        Column                              $column,
        array                               $columns,
        ColumnValueRegistryInterface        $registry,
        ExpressionLanguage                  $expressions,
        Generator                           $faker
    );

    public function resolve();
}
