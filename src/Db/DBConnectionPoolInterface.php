<?php

namespace Devian2011\Seeder\Db;

interface DBConnectionPoolInterface
{
    public function getDb(string $code): DBConnectionInterface;
}
