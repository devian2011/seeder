<?php

namespace Devian2011\Seeder\Expressions;

use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class InternalExpressionLanguage implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new EnvExpressionFunction(),
        ];
    }

}
