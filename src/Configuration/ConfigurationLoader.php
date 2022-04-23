<?php

namespace Devian2011\Seeder\Configuration;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader extends \Symfony\Component\ExpressionLanguage\ExpressionLanguage
{
    private string $templateDir;

    public function __construct(string $templateDir)
    {
        $this->templateDir = $templateDir;
    }

    private function parseFiles(): array
    {
        $configuration = [];
        $directory = new \RecursiveDirectoryIterator($this->templateDir);
        $iterator = new \RecursiveIteratorIterator($directory);
        /** @var \SplFileInfo $fileInfo */
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $conf = [];
                $ext = $fileInfo->getExtension();
                echo $fileInfo->getRealPath(), PHP_EOL;
                if (in_array($ext, ['yml', 'yaml'])) {
                    $conf = Yaml::parseFile($fileInfo->getRealPath());
                }
                if ($ext === 'json') {
                    $conf = json_decode(file_get_contents($fileInfo->getRealPath()), true);
                }
                if ($ext === 'php') {
                    $conf = require_once $fileInfo->getRealPath();
                }
                $configuration = array_merge_recursive($configuration, $conf);
            }
        }

        return $configuration;
    }

    public function build(ExpressionLanguage $expressionLanguage): Root
    {
        $configuration = $this->parseFiles();
        $databases = $this->buildDatabases($configuration['databases'], $expressionLanguage);
        $tables = $this->buildTables($configuration['tables']);

        return new Root($databases, $tables);
    }

    private function buildDatabases(array $dbs, ExpressionLanguage $expressionLanguage): array
    {
        $result = [];
        foreach ($dbs as $dbConf) {
            $result[] = new Database(
                $dbConf['code'],
                $expressionLanguage->evaluate($dbConf['dsn']),
                $expressionLanguage->evaluate($dbConf['user']),
                $expressionLanguage->evaluate($dbConf['password']),
                $dbConf['options'] ?? []
            );
        }

        return $result;
    }

    private function buildTables(array $tables): array
    {
        $result = [];
        foreach ($tables as $table) {
            $columns = $this->buildColumns($table['columns']);
            $result[] = new Table(
                $table['database'],
                $table['name'],
                $table['mods'],
                $columns,
                $table['rowQuantity'],
                $table['primaryKey']
            );
        }
        return $result;
    }

    private function buildColumns(array $columns): array
    {
        $result = [];
        foreach ($columns as $column) {
            $result[] = new Column($column['name'], $column['value']);
        }
        return $result;
    }
}
