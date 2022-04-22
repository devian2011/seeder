<?php

namespace Devian2011\Seeder\Db;

class DBConnectionConfigurationException extends \Exception
{
    public const DB_CONN_CONFIG_WRONG_CONFIG_OBJECT = 2000;
    public const DB_CONN_CONFIG_ERR_CONNECTION_ALREADY_EXISTS = 2001;
    public const DB_CONN_CONFIG_ERR_UNKNOWN_CONNECTION = 2002;
}
