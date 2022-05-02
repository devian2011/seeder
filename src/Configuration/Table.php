<?php

namespace Devian2011\Seeder\Configuration;

class Table
{
    /** @var string */
    private string $id;
    /** @var string */
    private string $database;
    /** @var string */
    private string $name;
    /** @var Column[] */
    private array $columns = [];
    /** @var RelativeColumn[] */
    private array $relations = [];
    /** @var string */
    private string $rowQuantity;
    /** @var string|array */
    private $primaryKey;
    /** @var Column[][] */
    private $fixed = [];
    /** @var bool */
    private bool $loadFromDb = false;

    /**
     * @param string $database
     * @param string $name
     * @param array $mods
     * @param Column[] $columns
     * @param RelativeColumn[] $relations
     * @param string $rowQuantity
     * @param Column[][] $fixed
     * @param string|array $primaryKey
     * @param bool $loadFromDb
     */
    public function __construct(
        string $database,
        string $name,
        array  $columns,
        array  $relations,
        string $rowQuantity,
        array  $fixed = [],
               $primaryKey = 'id',
        bool   $loadFromDb = false
    )
    {
        $this->id = sprintf("%s.%s", $database, $name);
        $this->database = $database;
        $this->name = $name;
        foreach ($columns as $column) {
            $this->columns[$column->getName()] = $column;
        }
        foreach ($relations as $relation) {
            $this->relations[$relation->getName()] = $relation;
        }
        $this->rowQuantity = $rowQuantity;
        $this->primaryKey = $primaryKey;
        $this->loadFromDb = $loadFromDb;

        foreach ($fixed as $row) {
            $cols = [];
            foreach ($row as $column) {
                $cols[$column->getName()] = $column;
            }
            $this->fixed[] = $cols;
        }
        $this->generateId($this);
    }

    private function generateId(Table $table): void
    {
        $this->id = self::generateTableId($table->getDatabase(), $table->getName());
    }

    public static function generateTableId(string $database, string $name): string
    {
        return sprintf("%s.%s", $database, $name);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return RelativeColumn[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @return array|string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return string
     */
    public function getRowQuantity(): string
    {
        return $this->rowQuantity;
    }

    /**
     * @return bool
     */
    public function isLoadFromDb(): bool
    {
        return $this->loadFromDb;
    }

    /**
     * @return Column[]
     */
    public function getFixed(): array
    {
        return $this->fixed;
    }
}
