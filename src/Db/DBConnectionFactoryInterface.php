<?php

namespace Devian2011\Seeder\Db;

interface DBConnectionFactoryInterface
{
    public function getDbConnection(string $code): DBConnectionInterface;
}
