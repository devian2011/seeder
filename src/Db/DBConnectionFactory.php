<?php

namespace Devian2011\Seeder\Db;

use Devian2011\Seeder\Configuration\Database;
use function Webmozart\Assert\Tests\StaticAnalysis\throws;

class DBConnectionFactory implements DBConnectionFactoryInterface
{
    /** @var Database[] */
    private array $configurations = [];

    /**
     * @param Database[] $databasesConfigurations
     * @throws DBConnectionConfigurationException
     */
    public function __construct(array $databasesConfigurations = [])
    {
        foreach ($databasesConfigurations as $configuration) {
            if(!$configuration instanceof Database){
                throw new DBConnectionConfigurationException(
                    "Wrong configuration class",
                    DBConnectionConfigurationException::DB_CONN_CONFIG_WRONG_CONFIG_OBJECT
                );
            }
            if (!empty($this->configurations[$configuration->getCode()])) {
                throw new DBConnectionConfigurationException(
                    "Database with code: {$configuration->getCode()}, already exists",
                    DBConnectionConfigurationException::DB_CONN_CONFIG_ERR_CONNECTION_ALREADY_EXISTS
                );
            }
            $this->configurations[$configuration->getCode()] = $configuration;
        }
    }

    /**
     * @param string $code
     * @return DBConnectionInterface
     * @throws DBConnectionConfigurationException
     */
    public function getDbConnection(string $code): DBConnectionInterface
    {
        if (empty($this->configurations[$code])) {
            throw new DBConnectionConfigurationException(
                "Unknown database connection with code: {$code}",
                DBConnectionConfigurationException::DB_CONN_CONFIG_ERR_UNKNOWN_CONNECTION
            );
        }
        return new DBConnection($this->configurations[$code]);
    }

}
