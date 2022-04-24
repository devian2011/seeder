<?php

namespace Devian2011\Seeder\Ast\Relations;

use Devian2011\Seeder\Configuration\Column;
use Devian2011\Seeder\Configuration\RelativeColumn;
use Devian2011\Seeder\Configuration\Table;

class TableDecorator
{
    private Table $table;
    private array $preparedFields = [];

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->table->getDatabase();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->table->getName();
    }

    /**
     * @return string[]
     */
    public function getMods(): array
    {
        return $this->table->getMods();
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->table->getColumns();
    }

    /**
     * @return RelativeColumn[]
     */
    public function getRelations(): array
    {
        return $this->table->getRelations();
    }

    /**
     * @return array|string
     */
    public function getPrimaryKey()
    {
        return $this->table->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getRowQuantity(): string
    {
        return $this->table->getRowQuantity();
    }

    /**
     * @return Column[]
     */
    public function getFixed(): array
    {
        return $this->table->getFixed();
    }

    /**
     * @return array Hash map - key it's a column name, value - it's column value
     */
    public function getPreparedFields(): array
    {
        return $this->preparedFields;
    }

}
