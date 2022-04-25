<?php

namespace Devian2011\Seeder\Tests\Loader;

use Devian2011\Seeder\Configuration\Table;
use Devian2011\Seeder\Loader\DataToDbRequestTransformer;
use PHPUnit\Framework\TestCase;

class DataToDbRequestTransformerTest extends TestCase
{

    public function testTransformDataToSql()
    {
        $table = new Table('admin', 'users', [], [], [], 2, [], 'id');
        $data = [
            ['login' => 'test', 'password' => 'pTest2'],
            ['login' => 'test2', 'password' => 'passwordTestSome']
        ];
        $result = (new DataToDbRequestTransformer())->transformDataToSql($table, $data);

        $this->assertArrayHasKey('sql', $result[0]);
        $this->assertArrayHasKey('data', $result[0]);
        $this->assertArrayHasKey('sql', $result[1]);
        $this->assertArrayHasKey('data', $result[1]);
        $this->assertCount(2, $result);

        $this->assertEquals('INSERT INTO users (login,password) VALUES (:login,:password)', $result[0]['sql']);
        $this->assertEquals([
            ':login' => 'test', ':password' => 'pTest2'
        ], $result[0]['data']);
        $this->assertEquals([
            ':login' => 'test2', ':password' => 'passwordTestSome'
        ], $result[1]['data']);
    }

}
