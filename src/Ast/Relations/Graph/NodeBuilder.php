<?php

namespace Devian2011\Seeder\Ast\Relations\Graph;

use Devian2011\Seeder\Configuration\Table;

class NodeBuilder implements NodeBuilderInterface
{

    public function build(Table $table, array &$tables, Graph $graph): Node
    {
        $node = new Node($table);
        $related = $table->getRelations();
        if (empty($related)) {
            return $node;
        }
        foreach ($related as $relativeColumn) {
            $relativeTableId = Table::generateTableId($relativeColumn->getDatabase(), $relativeColumn->getTable());
            if ($graph->hasNode($relativeTableId)) {
                $relation = new Relation($graph->getNode($relativeTableId), $node, $relativeColumn);
            } else {
                $parentNode = self::build($tables[$relativeTableId], $tables, $graph);
                $relation = new Relation($parentNode, $node, $relativeColumn);
            }
            $node->addRelation($relation);
        }

        return $node;
    }

}
