<?php

namespace Devian2011\Seeder\Ast\Schema\Graph;

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
            $parentNode = $graph->hasNode($relativeTableId)
                ? $graph->getNode($relativeTableId)
                : self::build($tables[$relativeTableId], $tables, $graph);

            $relation = new Relation($parentNode, $node, $relativeColumn);
            $parentNode->addChild($node->getId());
            $node->addRelation($relation);
        }

        return $node;
    }

}
