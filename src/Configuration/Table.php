<?php

namespace Devian2011\Seeder\Configuration;

class Table
{
    public const TABLE_MODE_PREDEFINED = 'predefined';
    public const TABLE_MODE_TEST = 'test';
    public const TABLE_MODE_FAKE = 'fake';

    /** @var string */
    private string $id;
    /** @var string */
    private string $database;
    /** @var string */
    private string $name;
    /** @var string[] */
    private array $mods = [];
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

    /**
     * @param string $database
     * @param string $name
     * @param array $mods
     * @param Column[] $columns
     * @param RelativeColumn[] $relations
     * @param string $rowQuantity
     * @param Column[][] $fixed
     * @param string|array $primaryKey
     */
    public function __construct(
        string $database,
        string $name,
        array  $mods,
        array  $columns,
        array  $relations,
        string $rowQuantity,
        array  $fixed = [],
               $primaryKey = 'id'
    )
    {
        $this->id = sprintf("%s.%s", $database, $name);
        $this->database = $database;
        $this->name = $name;
        $this->mods = $mods;
        foreach ($columns as $column) {
            $this->columns[$column->getName()] = $column;
        }
        foreach ($relations as $relation) {
            $this->relations[$relation->getName()] = $relation;
        }
        $this->rowQuantity = $rowQuantity;
        $this->primaryKey = $primaryKey;

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
     * @return string[]
     */
    public function getMods(): array
    {
        return $this->mods;
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
     * This method used for OneToOne relation.
     * Because in this relation type table rows quantity must be equal
     *
     * @param int $rowQuantity
     * @return void
     */
    public function setRowQuantity(int $rowQuantity): void
    {
        $this->rowQuantity = $rowQuantity;
    }

    /**
     * @return Column[]
     */
    public function getFixed(): array
    {
        return $this->fixed;
    }
}
