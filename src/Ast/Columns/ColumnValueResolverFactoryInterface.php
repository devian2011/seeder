<?php

namespace Devian2011\Seeder\Ast\Columns;

use Devian2011\Seeder\Configuration\Column;
use Faker\Generator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

interface ColumnValueResolverFactoryInterface
{
    public function getColumnValueResolver(
        Column                              $column,
        array                               $columns,
        ColumnValueRegistryInterface        $registry,
        ExpressionLanguage                  $expressions,
        Generator                           $faker): ColumnValueResolverInterface;
}
