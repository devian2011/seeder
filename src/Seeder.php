<?php

namespace Devian2011\Seeder;

use Devian2011\Seeder\Ast\Columns\ColumnValueRegistryFactory;
use Devian2011\Seeder\Ast\Columns\ColumnValueResolverFactory;
use Devian2011\Seeder\Ast\Columns\TableColumnsResolver;
use Devian2011\Seeder\Ast\Schema\Graph\Graph;
use Devian2011\Seeder\Ast\Schema\Graph\GraphBuilder;
use Devian2011\Seeder\Ast\Schema\Graph\NodeBuilder;
use Devian2011\Seeder\Ast\Schema\Graph\NodeBuilderInterface;
use Devian2011\Seeder\Configuration\ConfigurationLoader;
use Devian2011\Seeder\Configuration\Root;
use Devian2011\Seeder\Configuration\Table;
use Devian2011\Seeder\Db\DBConnectionFactory;
use Devian2011\Seeder\Db\DBConnectionPool;
use Devian2011\Seeder\Db\DBConnectionPoolInterface;
use Devian2011\Seeder\Expressions\InternalExpressionLanguage;
use Devian2011\Seeder\Loader\DatabaseLoader;
use Faker\Factory;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Seeder
{
    private const AVAILABLE_MODES = [
        Table::TABLE_MODE_FAKE,
        Table::TABLE_MODE_PREDEFINED,
        Table::TABLE_MODE_TEST
    ];

    private ConfigurationLoader $configurationLoader;
    private array $params;

    public function __construct(string $templateDir, string $mode, array $paramFiles)
    {
        if (!file_exists($templateDir) || !is_dir($templateDir)) {
            throw new \RuntimeException("Template dir is not directory or does not exists. Path {$templateDir}");
        }
        if (!in_array($mode, self::AVAILABLE_MODES)) {
            throw new \RuntimeException("Unknown seeder mode: {$mode}");
        }
        foreach ($paramFiles as $pFile) {
            if (!file_exists($pFile) || !is_file($pFile)) {
                throw new \RuntimeException("File with params does not exist. File: {$pFile}");
            }
        }

        $this->configurationLoader = new ConfigurationLoader($templateDir);
        $this->params = $paramFiles;
    }


    /**
     * @param ExpressionFunctionProviderInterface|null $functionProvider
     * @return void
     * @throws Db\DBConnectionConfigurationException
     * @throws \Throwable
     */
    public function run(ExpressionFunctionProviderInterface $functionProvider = null)
    {
        $expressions = $this->buildExpressionLanguage($functionProvider);
        $config = $this->loadConfig($expressions);
        $connectionPool = $this->initConnectionPool($config->getDatabases());
        $schema = $this->buildSchema(new NodeBuilder(), $config->getTables());
        $tableValuesResolver = $this->buildTableColumnsResolver($expressions);

        $loader = new DatabaseLoader(
            $connectionPool,
            $schema,
            $tableValuesResolver
        );

        $loader->load();
    }

    private function buildExpressionLanguage(ExpressionFunctionProviderInterface $functionProvider = null): ExpressionLanguage
    {
        $expressions = new ExpressionLanguage(null, [
            new InternalExpressionLanguage()
        ]);
        if (!empty($functionProvider)) {
            $expressions->registerProvider($functionProvider);
        }

        return $expressions;
    }

    private function loadConfig(ExpressionLanguage $expressions): Root
    {
        (new Dotenv())->load(...$this->params);
        return $this->configurationLoader->build($expressions);
    }

    /**
     * @throws Db\DBConnectionConfigurationException
     */
    private function initConnectionPool(array $databases): DBConnectionPoolInterface
    {
        $databaseConnectionFactory = new DBConnectionFactory($databases);
        return new DBConnectionPool($databaseConnectionFactory);
    }

    private function buildSchema(NodeBuilderInterface $nodeBuilder, array $tables): Graph
    {
        return (new GraphBuilder($tables, $nodeBuilder))->getGraph();
    }

    private function buildTableColumnsResolver(ExpressionLanguage $expressionLanguage): TableColumnsResolver
    {
        return new TableColumnsResolver(
            Factory::create(),
            $expressionLanguage,
            new ColumnValueRegistryFactory(),
            new ColumnValueResolverFactory()
        );
    }

}
