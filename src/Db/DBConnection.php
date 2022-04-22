<?php

namespace Devian2011\Seeder\Db;

use Devian2011\Seeder\Configuration\Database;

class DBConnection implements DBConnectionInterface
{
    private \PDO $conn;
    private Database $config;

    public function __construct(Database $config)
    {
        $this->config = $config;
    }

    public function getCode(): string
    {
        return $this->config->getCode();
    }

    public function isConnected(): bool
    {
        return !empty($this->conn);
    }

    public function getConn(): \PDO
    {
        return $this->conn;
    }

    /**
     * @throws DBConnectionException
     */
    public function connect(): void
    {
        try {
            $this->conn = new \PDO(
                $this->config->getDsn(),
                $this->config->getUser(),
                $this->config->getPassword(),
                $this->config->getOptions()
            );
        } catch (\PDOException $PDOException) {
            throw new DBConnectionException(
                "Cannot connect to db with code: {$this->getCode()}. Err: {$PDOException->getMessage()}",
                DBConnectionException::DB_CONNECTION_PDO_ERROR,
                $PDOException
            );
        }
    }


}
