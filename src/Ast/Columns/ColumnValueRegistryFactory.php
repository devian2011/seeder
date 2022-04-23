<?php

namespace Devian2011\Seeder\Ast\Columns;

class ColumnValueRegistryFactory implements ColumnValueRegistryFactoryInterface
{
    public function getRegistry(): ColumnValueRegistryInterface
    {
        return new ColumnValueRegistry();
    }
}
