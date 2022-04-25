<?php

namespace Devian2011\Seeder\Loader;

use Devian2011\Seeder\Ast\Columns\TableColumnsResolver;
use Devian2011\Seeder\Ast\Schema\Graph\Node;
use Devian2011\Seeder\Ast\Schema\Graph\Graph;
use Devian2011\Seeder\Configuration\RelativeColumn;
use Devian2011\Seeder\Db\DBConnectionPoolInterface;
use Devian2011\Seeder\Events\Event;
use Devian2011\Seeder\Events\EventInsertionRowSuccess;
use Devian2011\Seeder\Events\EventsDispatcherInterface;
use Devian2011\Seeder\Events\ExceptionEvent;
use Devian2011\Seeder\Helpers\Randomizer;

class DatabaseLoader
{
    public const EVENT_DATABASE_LOAD_ERROR = 'db.load.error';
    public const EVENT_DATABASE_INSERT_SUCCESS = 'db.insert.success';


    private DBConnectionPoolInterface $connectionPool;
    private TableEraser $eraser;
    private Graph $schema;
    private TableColumnsResolver $columnsResolver;
    private DataToDbRequestTransformer $transformer;
    private EventsDispatcherInterface $eventsDispatcher;

    public function __construct(
        DBConnectionPoolInterface $connectionPool,
        Graph                     $schema,
        TableColumnsResolver      $columnsResolver,
        EventsDispatcherInterface $eventsDispatcher
    )
    {
        $this->connectionPool = $connectionPool;
        $this->eraser = new TableEraser($connectionPool);
        $this->schema = $schema;
        $this->columnsResolver = $columnsResolver;
        $this->transformer = new DataToDbRequestTransformer();
        $this->eventsDispatcher = $eventsDispatcher;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function load()
    {
        try {
            //TODO: Think about optimize memory usage
            $loadedTableData = [];
            foreach ($this->schema->getNodes() as $node) {
                if (isset($loadedTableData[$node->getId()])) continue;

                $this->eraser->erase($node, $this->schema);
                $this->fillDbData($node, $loadedTableData);
            }
            $this->connectionPool->poolCommit();
        } catch (\Throwable $throwable) {
            $this->connectionPool->poolRollback();
            throw $throwable;
        }

    }

    private function getDataFromDb(Node $node): array
    {
        return $this->connectionPool
            ->getDb($node->getTable()->getDatabase())
            ->getConn()
            ->query("SELECT * FROM {$node->getTable()->getName()}")
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param Node $node
     * @param array $loadedTableData
     * @return void
     * @throws DataLoaderException
     * TODO: Think about optimize algorithm
     */
    private function fillDbData(Node $node, array &$loadedTableData)
    {
        //Skip handle if this data has been already loaded
        if (isset($loadedTableData[$node->getId()])) return;

        //In this case we load data from db. Loaded data can not have relations to fake data
        if ($node->getTable()->isLoadFromDb()) {
            $loadedTableData[$node->getId()] = $this->getDataFromDb($node);
            return;
        }

        $data = $this->columnsResolver->generate($node->getTable());

        if ($node->hasRelations()) {
            foreach ($node->getRelations() as $relation) {
                //If parent is not already loaded - load
                if (!isset($loadedTableData[$relation->getParent()->getId()])) {
                    $this->fillDbData($relation->getParent(), $loadedTableData);
                }

                if ($relation->getRelativeColumn()->getType() === RelativeColumn::RELATION_ONE_TO_ONE) {
                    //TODO: Move it to configuration checker
                    if ($node->getTable()->getRowQuantity() > $relation->getParent()->getTable()->getRowQuantity()) {
                        throw new DataLoaderException(
                            sprintf(
                                "Table: %s has One To One relation to %s. But child's rows quantity (%d) greater than parent rows quantity (%d)",
                                $node->getTable()->getId(),
                                $relation->getParent()->getTable()->getId(),
                                $node->getTable()->getRowQuantity(),
                                $relation->getParent()->getTable()->getRowQuantity()
                            )
                        );
                    }
                    foreach ($loadedTableData[$relation->getParent()->getId()] as $i => $parentRow) {
                        $data[$i][$relation->getRelativeColumn()->getName()] = $parentRow[$relation->getRelativeColumn()->getColumn()];
                    }
                } else if ($relation->getRelativeColumn()->getType() === RelativeColumn::RELATION_MANY_TO_ONE) {
                    foreach ($data as $i => $row) {
                        $randomItem = Randomizer::randomArrayItem($loadedTableData[$relation->getParent()->getId()]);
                        $data[$i][$relation->getRelativeColumn()->getName()] = $randomItem[$relation->getRelativeColumn()->getColumn()];
                    }
                } else if ($relation->getRelativeColumn()->getType() === RelativeColumn::RELATION_MANY_TO_MANY) {
                    throw new DataLoaderException("Unfortunately Many-To-Many relation does not support yet. You can create table that depends from each of parent tables");
                } else {
                    throw new DataLoaderException("Unknown relation type: {$relation->getRelativeColumn()->getType()}");
                }
            }
        }

        $loadedTableData[$node->getId()] = $this->insertData($node, $data);
    }

    /**
     * @param Node $node
     * @param array $data
     * @return array
     * @throws DataLoaderException
     */
    private function insertData(Node $node, array $data): array
    {
        $sqlData = $this->transformer->transformDataToSql($node->getTable(), $data);
        $conn = $this->connectionPool
            ->getDb($node->getTable()->getDatabase())
            ->getConn();
        foreach ($sqlData as $i => $row) {
            $query = $conn->prepare($row['sql']);
            if (!$query->execute($row['data'])) {
                $this->eventsDispatcher->notify(self::EVENT_DATABASE_LOAD_ERROR, new Event(
                    'Failed database insertion',
                    [
                        'table' => $node->getTable()->getName(),
                        'database' => $node->getTable()->getDatabase(),
                        'sql' => $row['sql'],
                        'data' => $row['data'],
                        'message' => $query->errorInfo()[2],
                    ]
                ));
                throw new DataLoaderException($query->errorInfo()[2]);
            }

            $data[$i][$node->getTable()->getPrimaryKey()] = $conn->lastInsertId();
            $this->eventsDispatcher->notify(self::EVENT_DATABASE_INSERT_SUCCESS, new EventInsertionRowSuccess(
                'Success row insertion',
                $node->getTable()->getDatabase(),
                $node->getTable()->getName(),
                $row['sql'],
                $data[$i]
            ));
        }

        return $data;
    }
}
