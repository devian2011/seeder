<?php

namespace Devian2011\Seeder\Tests\Db;

use Devian2011\Seeder\Configuration\Database;
use Devian2011\Seeder\Db\DBConnectionConfigurationException;
use Devian2011\Seeder\Db\DBConnectionFactory;
use Devian2011\Seeder\Db\DBConnectionInterface;
use PHPUnit\Framework\TestCase;

class DBConnectionFactoryTest extends TestCase
{

    /**
     * @throws DBConnectionConfigurationException
     */
    public function testGetDbConnection()
    {
        $dbConnectionFactory = new DBConnectionFactory([
            new Database("first", "dsn", "user", "password", []),
            new Database("second", "dsn1", "user1", "password1", [])
        ]);
        $dbConn = $dbConnectionFactory->getDbConnection('first');
        $this->assertInstanceOf(DBConnectionInterface::class, $dbConn);
        $this->assertEquals('first', $dbConn->getCode());
        $dbConn2 = $dbConnectionFactory->getDbConnection('second');
        $this->assertInstanceOf(DBConnectionInterface::class, $dbConn2);
        $this->assertEquals('second', $dbConn2->getCode());
    }

    public function testWrongConfigurationParamsConstruct()
    {
        $this->expectException(DBConnectionConfigurationException::class);
        $this->expectExceptionCode(DBConnectionConfigurationException::DB_CONN_CONFIG_WRONG_CONFIG_OBJECT);
        new DBConnectionFactory([
            ['some', 'wrong', 'config'],
        ]);
    }

    public function testWrongConfigurationConfigAlreadyExistsConstruct()
    {
        $this->expectException(DBConnectionConfigurationException::class);
        $this->expectExceptionCode(DBConnectionConfigurationException::DB_CONN_CONFIG_ERR_CONNECTION_ALREADY_EXISTS);
        new DBConnectionFactory([
            new Database("first", "dsn", "user", "password", []),
            new Database("first", "dsn1", "user1", "password1", [])
        ]);
    }

}
