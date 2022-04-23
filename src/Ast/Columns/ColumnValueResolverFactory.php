<?php

namespace Devian2011\Seeder\Ast\Columns;

use Devian2011\Seeder\Configuration\Column;
use Faker\Generator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ColumnValueResolverFactory implements ColumnValueResolverFactoryInterface
{
    public function getColumnValueResolver(
        Column                              $column,
        array                               $columns,
        ColumnValueRegistryInterface        $registry,
        ExpressionLanguage                  $expressions,
        Generator                           $faker): ColumnValueResolverInterface
    {
        return new ColumnValueResolver(
            $this,
            $column,
            $columns,
            $registry,
            $expressions,
            $faker
        );
    }

}
