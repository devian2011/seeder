<?php

namespace Devian2011\Seeder\Db;

class DBConnectionPool implements DBConnectionPoolInterface
{
    private DBConnectionFactoryInterface $connectionFactory;
    /** @var DBConnection[] */
    private array $pool;

    public function __construct(DBConnectionFactoryInterface $connectionFactory)
    {
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * @param string $code
     * @return DBConnectionInterface
     * @throws DBConnectionException
     */
    public function getDb(string $code): DBConnectionInterface
    {
        if (!empty($this->pool[$code])) {
            if (!$this->pool[$code]->isConnected()) {
                $this->pool[$code]->connect();
            }
            return $this->pool[$code];
        }

        $conn = $this->connectionFactory->getDbConnection($code);
        if (!$conn->isConnected()) {
            $conn->connect();
        }
        $this->pool[$code] = $conn;
        return $this->pool[$code];
    }

}
