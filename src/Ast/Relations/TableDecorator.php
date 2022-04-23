<?php

namespace Devian2011\Seeder\Ast\Relations;

use Devian2011\Seeder\Configuration\Table;

class TableDecorator
{
    private Table $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }
}
