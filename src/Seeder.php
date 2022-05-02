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
use Devian2011\Seeder\Events\Event;
use Devian2011\Seeder\Events\EventDispatcher;
use Devian2011\Seeder\Events\EventsDispatcherInterface;
use Devian2011\Seeder\Events\Listeners\DatabaseInsertionFailListener;
use Devian2011\Seeder\Events\Listeners\DatabaseInsertRowSuccessListener;
use Devian2011\Seeder\Events\Listeners\SeederProgressListener;
use Devian2011\Seeder\Expressions\InternalExpressionLanguage;
use Devian2011\Seeder\Loader\DatabaseLoader;
use Devian2011\Seeder\Output\OutputInterface;
use Faker\Factory;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Seeder
{
    private ConfigurationLoader $configurationLoader;
    private array $params;

    public function __construct(string $templateDir, array $paramFiles)
    {
        if (!file_exists($templateDir) || !is_dir($templateDir)) {
            throw new \RuntimeException("Template dir is not directory or does not exists. Path {$templateDir}");
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
    public function run(
        OutputInterface                     $output,
        ExpressionFunctionProviderInterface $functionProvider = null,
        array                               $eventHandlers = []
    )
    {
        $eventDispatcher = $this->buildEventDispatcher($output, $eventHandlers);
        $eventDispatcher->notify(
            SeederEvents::EVENT_SEEDER_START,
            new Event('Seeder started...', [])
        );
        $expressions = $this->buildExpressionLanguage($functionProvider);
        $config = $this->loadConfig($expressions);
        $eventDispatcher->notify(
            SeederEvents::EVENT_SEEDER_CONFIG_LOADED,
            new Event('Config has been loaded...', ['config' => $config])
        );
        $connectionPool = $this->initConnectionPool($config->getDatabases());
        $schema = $this->buildSchema(new NodeBuilder(), $config->getTables());
        $eventDispatcher->notify(
            SeederEvents::EVENT_SEEDER_SCHEMA_BUILT,
            new Event('Schema has been built...', ['config' => $config])
        );
        $tableValuesResolver = $this->buildTableColumnsResolver($expressions);

        $loader = new DatabaseLoader(
            $connectionPool,
            $schema,
            $tableValuesResolver,
            $eventDispatcher
        );
        $eventDispatcher->notify(
            SeederEvents::EVENT_SEEDER_LOADING_DATA,
            new Event('Start data insertion', ['config' => $config])
        );
        $loader->load();
        $eventDispatcher->notify(
            SeederEvents::EVENT_SEEDER_DATA_LOADED,
            new Event('Data has been loaded', ['config' => $config])
        );
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

    public function buildEventDispatcher(OutputInterface $output, array $eventHandlers = []): EventsDispatcherInterface
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->subscribe(new DatabaseInsertionFailListener($output));
        $dispatcher->subscribe(new DatabaseInsertRowSuccessListener($output));
        $dispatcher->subscribe(new SeederProgressListener($output));
        foreach ($eventHandlers as $handler) {
            $dispatcher->subscribe($handler);
        }
        return $dispatcher;
    }

}
