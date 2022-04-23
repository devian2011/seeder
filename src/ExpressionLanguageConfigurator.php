<?php

namespace Devian2011\Seeder;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionLanguageConfigurator
{
    public static function configure(): ExpressionLanguage
    {

        $expressionLanguage = new ExpressionLanguage();
        $expressionLanguage->register(
            'env',
            function ($param) {
                return func_get_args();
            },
            function ($arguments, $str) {
                return !empty($_ENV[$str]) ? $_ENV[$str] : null;
            }
        );
        $expressionLanguage->register(
            'relation',
            function ($db, $table, $column) {
                return func_get_args();
            },
            function ($args, $relation, $db, $table, $column) {
                var_export([$relation, $db, $table, $column]);die();
                return [$relation, $db, $table, $column];
            }
        );


        return $expressionLanguage;
    }
}
