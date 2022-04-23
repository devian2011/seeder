<?php

namespace Devian2011\Seeder\Tests\Ast;

use Devian2011\Seeder\Ast\Columns\ColumnValueRegistry;
use Devian2011\Seeder\Ast\Columns\ColumnValueResolverException;
use PHPUnit\Framework\TestCase;

class ColumnValueRegistryTest extends TestCase
{

    public function testGetColumnValue()
    {
        $this->expectException(ColumnValueResolverException::class);
        $this->expectDeprecationMessage("Try to take value for column: col2 which is not exists");

        $columnRegistry = new ColumnValueRegistry();
        $columnRegistry->setColumnValue('col1', 'val1');

        $this->assertTrue($columnRegistry->isValueExists('col1'));
        $this->assertFalse($columnRegistry->isValueExists('col2'));
        $this->assertEquals( 'val1', $columnRegistry->getValue('col1'));
        $columnRegistry->getValue('col2');
        $this->assertEquals(['col1' => 'val1'], $columnRegistry->asArray());
    }

}
