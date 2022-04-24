<?php

namespace Devian2011\Seeder\Ast\Relations\Graph;

use Devian2011\Seeder\Configuration\Table;

interface NodeBuilderInterface
{
    public function build(Table $table, array &$tables, Graph $graph): Node;
}
