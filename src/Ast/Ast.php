<?php

namespace Devian2011\Seeder\Ast;

use Devian2011\Seeder\Configuration\Root;
use Devian2011\Seeder\Configuration\Table;

class Ast
{
    /** @var Table[] */
    private array $tables;

    /**
     * @param Table[] $tables
     */
    public function __construct(array $tables)
    {
        $this->tables = $tables;
    }



}
