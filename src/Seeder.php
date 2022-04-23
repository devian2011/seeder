<?php

namespace Devian2011\Seeder;

use Devian2011\Seeder\Configuration\ConfigurationLoader;
use Devian2011\Seeder\Configuration\Table;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Seeder
{

    private const AVAILABLE_MODES = [Table::TABLE_MODE_FAKE, Table::TABLE_MODE_PREDEFINED, Table::TABLE_MODE_TEST];

    private ConfigurationLoader $configurationLoader;
    private array $params;
    private ExpressionLanguage $expressionLanguage;

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
        $this->expressionLanguage = ExpressionLanguageConfigurator::configure();
    }


    public function run()
    {
        (new Dotenv())->load(...$this->params);
        $config = $this->configurationLoader->build($this->expressionLanguage);
        var_export($config);
    }

}
