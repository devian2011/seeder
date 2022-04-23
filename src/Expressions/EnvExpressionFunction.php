<?php

namespace Devian2011\Seeder\Expressions;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class EnvExpressionFunction extends ExpressionFunction
{

    public function __construct()
    {
        parent::__construct(
            'env',
            function ($param) {
                return func_get_args();
            },
            function ($arguments, $str) {
                return !empty($_ENV[$str]) ? $_ENV[$str] : null;
            }
        );
    }

}
