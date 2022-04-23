<?php

namespace Devian2011\Seeder\Ast\Columns;

interface ColumnValueRegistryFactoryInterface
{
    public function getRegistry(): ColumnValueRegistryInterface;
}
