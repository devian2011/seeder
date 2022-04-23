<?php

namespace Devian2011\Seeder\Expressions;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class RandExpressionFunction extends ExpressionFunction
{

    public function __construct()
    {
        parent::__construct(
            'rand',
            function ($param) {
                return func_get_args();
            },
            function ($arguments, $begin, $end) {
                return mt_rand((int)$begin, (int)$end);
            }
        );
    }

}
