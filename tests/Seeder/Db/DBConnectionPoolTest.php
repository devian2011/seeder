<?php

namespace Devian2011\Seeder\Tests\Db;

use Devian2011\Seeder\Db\DBConnectionFactoryInterface;
use Devian2011\Seeder\Db\DBConnectionInterface;
use Devian2011\Seeder\Db\DBConnectionPool;
use PHPUnit\Framework\TestCase;

class DBConnectionPoolTest extends TestCase
{
    public function testGetDbAlreadyInPool()
    {
        $dbConn = $this->createMock(DBConnectionInterface::class);
        $dbConn->method('getCode')->willReturn('first');
        $dbConn->method('isConnected')->willReturn(true);

        $factory = $this->createMock(DBConnectionFactoryInterface::class);
        $factory->method('getDbConnection')->willReturnCallback(function ($code) use ($dbConn) {
            if ($code === 'first') {
                return $dbConn;
            }
            return null;
        });

        $dbPool = new DBConnectionPool($factory);
        $conn = $dbPool->getDb('first');
        $this->assertInstanceOf(DBConnectionInterface::class, $conn);
        $this->assertEquals('first', $conn->getCode());

        $conn2 = $dbPool->getDb('first');
        $this->assertInstanceOf(DBConnectionInterface::class, $conn2);
        $this->assertEquals('first', $conn2->getCode());

        $this->assertEquals($dbConn, $conn);
        $this->assertEquals($dbConn, $conn2);
        $this->assertEquals($conn, $conn2);
    }
}
