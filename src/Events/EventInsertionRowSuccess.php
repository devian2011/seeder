<?php

namespace Devian2011\Seeder\Events;

class EventInsertionRowSuccess implements EventInterface
{
    private string $message, $database, $table, $sql;
    private array $data;

    public function __construct(string $message, string $database, string $table, string $sql, array $data)
    {
        $this->message = $message;
        $this->database = $database;
        $this->table = $table;
        $this->sql = $sql;
        $this->data = $data;
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
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return [
            'database' => $this->database,
            'table' => $this->table,
            'sql' => $this->sql,
            'data' => $this->data,
        ];
    }


}
