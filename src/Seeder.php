<?php

namespace Devian2011\Seeder;

use Devian2011\Seeder\Configuration\ConfigurationLoader;
use Devian2011\Seeder\Configuration\Table;
use Devian2011\Seeder\Expressions\InternalExpressionLanguage;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

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


    public function run(ExpressionFunctionProviderInterface $functionProvider = null)
    {
        $expressions = new ExpressionLanguage(null, [
            new InternalExpressionLanguage()
        ]);
        if (!empty($functionProvider)) {
            $expressions->registerProvider($functionProvider);
        }

        (new Dotenv())->load(...$this->params);
        $config = $this->configurationLoader->build($expressions);
        var_export($config);die();
    }

}
