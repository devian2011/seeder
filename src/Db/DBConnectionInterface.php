<?php

namespace Devian2011\Seeder\Db;

interface DBConnectionInterface
{
    public function getCode(): string;

    public function isConnected(): bool;

    /**
     * @return void
     * @throws DBConnectionException
     */
    public function connect(): void;
    public function getConn(): \PDO;
}
