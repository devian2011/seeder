<?php

namespace Devian2011\Seeder\Tests\Ast\Columns;

use Devian2011\Seeder\Ast\Columns\ColumnValueRegistry;
use Devian2011\Seeder\Ast\Columns\ColumnValueResolver;
use Devian2011\Seeder\Ast\Columns\ColumnValueResolverException;
use Devian2011\Seeder\Ast\Columns\ColumnValueResolverFactory;
use Devian2011\Seeder\Configuration\Column;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ColumnValueResolverTest extends TestCase
{

    public function testColumnResolver()
    {
        $expressions = new ExpressionLanguage();
        $faker = new Generator();
        $columnValueFactory = new ColumnValueResolverFactory();
        $registry = new ColumnValueRegistry();
        $columnBalanceAfter = new Column(
            'balance_after',
            'context.balance_before + context.amount',
            ['balance_before', 'amount']
        );
        $columnBalanceBefore = new Column(
            'balance_before',
            '1000',
            []
        );
        $columnAmount = new Column(
            'amount',
            '100',
            []
        );

        $resolver = new ColumnValueResolver(
            $columnValueFactory,
            $columnBalanceAfter,
            [
                'balance_after' => $columnBalanceAfter,
                'balance_before' => $columnBalanceBefore,
                'amount' => $columnAmount
            ],
            $registry,
            $expressions,
            $faker
        );

        $result = $resolver->resolve();
        $this->assertEquals(1100, $result);
    }

    public function testColumnDepth2Resolver()
    {
        $expressions = new ExpressionLanguage();
        $faker = new Generator();
        $registry = new ColumnValueRegistry();
        $columnResolverFactory = new ColumnValueResolverFactory();
        $columnPercent = new Column(
            'percent',
            '(context.balance_after / context.balance_before) * 100',
            ['balance_after', 'balance_before']
        );
        $columnBalanceAfter = new Column(
            'balance_after',
            'context.balance_before + context.amount',
            ['balance_before', 'amount']
        );
        $columnBalanceBefore = new Column(
            'balance_before',
            '1000',
            []
        );
        $columnAmount = new Column(
            'amount',
            '100',
            []
        );

        $resolver = new ColumnValueResolver(
            $columnResolverFactory,
            $columnPercent,
            [
                'balance_after' => $columnBalanceAfter,
                'balance_before' => $columnBalanceBefore,
                'amount' => $columnAmount,
                'percent' => $columnPercent,
            ],
            $registry,
            $expressions,
            $faker
        );

        $result = $resolver->resolve();
        $this->assertEquals(110, $result);
    }

    public function testUnknownAddictionColumnResolver()
    {
        $this->expectException(ColumnValueResolverException::class);
        $this->expectExceptionMessage("Unknown dependence column: amount for resolver");

        $expressions = new ExpressionLanguage();
        $faker = new Generator();
        $registry = new ColumnValueRegistry();
        $columnValueFactory = new ColumnValueResolverFactory();
        $columnBalanceAfter = new Column(
            'balance_after',
            'context.balance_before + context.amount',
            ['balance_before', 'amount']
        );
        $columnBalanceBefore = new Column(
            'balance_before',
            '1000',
            []
        );

        $resolver = new ColumnValueResolver(
            $columnValueFactory,
            $columnBalanceAfter,
            [
                'balance_after' => $columnBalanceAfter,
                'balance_before' => $columnBalanceBefore,
            ],
            $registry,
            $expressions,
            $faker
        );

        $resolver->resolve();
    }

}
