<?php

namespace Devian2011\Seeder\Tests\Ast;

use Devian2011\Seeder\Ast\Columns\ColumnValueRegistryFactory;
use Devian2011\Seeder\Ast\Columns\ColumnValueResolverFactory;
use Devian2011\Seeder\Ast\TableColumnsResolver;
use Devian2011\Seeder\Configuration\Column;
use Devian2011\Seeder\Configuration\Table;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class TableColumnsResolverTest extends TestCase
{
    public function testInsertionData()
    {

        $table = new Table(
            "default",
            "users",
            ["dev", "fake"],
            [
                new Column(
                    "id",
                    "auto_increment",
                    []
                ),
                new Column(
                    "balance_before",
                    "1000",
                    [

                    ]
                ),
                new Column(
                    "balance_after",
                    "context.balance_before + context.amount",
                    [
                        'balance_before',
                        'amount'
                    ]
                ),
                new Column(
                    "amount",
                    "100",
                    []
                ),
                new Column(
                    "percent",
                    "(context.balance_after / context.balance_before) * 100",
                    [
                        'balance_after',
                        'balance_before'
                    ]
                ),
            ],
            [],
            2,
            "id"
        );


        $insertion = new TableColumnsResolver(
            new Generator(),
            new ExpressionLanguage(),
            new ColumnValueRegistryFactory(),
            new ColumnValueResolverFactory()
        );

        $fillData = $insertion->generate($table);
        $this->assertCount($table->getRowQuantity(), $fillData);
        for ($c = 0; $c < $table->getRowQuantity(); $c++) {
            $this->assertArrayNotHasKey('id', $fillData[$c]);
            $this->assertEquals(1000, $fillData[$c]['balance_before']);
            $this->assertEquals(1100, $fillData[$c]['balance_after']);
            $this->assertEquals(100, $fillData[$c]['amount']);
            $this->assertEquals(110, $fillData[$c]['percent']);
        }
    }
}
