<?php

namespace Devian2011\Seeder\Db;

class DBConnectionPool implements DBConnectionPoolInterface
{
    /** @var DBConnectionFactoryInterface */
    private DBConnectionFactoryInterface $connectionFactory;
    /** @var DBConnection[] */
    private array $pool = [];

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
                $this->pool[$code]->getConn()->beginTransaction();
            }
            return $this->pool[$code];
        }

        $conn = $this->connectionFactory->getDbConnection($code);
        if (!$conn->isConnected()) {
            $conn->connect();
            $conn->getConn()->beginTransaction();
        }
        $this->pool[$code] = $conn;
        return $this->pool[$code];
    }

    public function poolCommit()
    {
        foreach ($this->pool as $conn){
            $conn->getConn()->commit();
        }
    }

    public function poolRollback()
    {
        foreach ($this->pool as $conn){
            $conn->getConn()->rollBack();
        }
    }

}
