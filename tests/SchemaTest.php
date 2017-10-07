<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;
use Plasm\Schema;
use Plasm\Tests\Fixtures\EmptySchema;
use Plasm\Tests\Fixtures\TestSchema;

final class SchemaTest extends TestCase
{
    /** @test */
    function can_be_created_empty()
    {
        $this->assertInstanceOf(
            Schema::class,
            new EmptySchema()
        );
    }

    /** @test */
    function can_be_created()
    {
        $this->assertInstanceOf(
            Schema::class,
            new TestSchema()
        );
    }

    /** @test */
    function gets_fields()
    {
        $schema = new MySchema();

        $this->assertArrayHasKey('name', $schema->fields());
        $this->assertTrue(count($schema->fields()) == 1);
    }

    /** @test */
    function array_accessor()
    {
        $schema = new MySchema();

        $this->assertArrayHasKey('name', $schema);
        $this->assertTrue($schema['name'] === ['type' => 'string']);
        // bla
        $this->assertTrue($schema->offsetExists('name'));
        $this->assertTrue($schema->offsetGet('name') === ['type' => 'string']);
        $this->assertTrue($schema->offsetExists('name'));
        $this->assertTrue($schema->offsetExists('name'));
    }

    /** @test */
    function array_accessor_blocks_array_set()
    {
        $this->expectException(\Exception::class);

        $schema = new MySchema();
        $schema['name'] = 'foo';
    }

    /** @test */
    function array_accessor_blocks_set()
    {
        $this->expectException(\Exception::class);

        $schema = new MySchema();
        $schema->offsetSet('name', 'foo');
    }

    /** @test */
    function array_accessor_blocks_array_unset()
    {
        $this->expectException(\Exception::class);

        $schema = new MySchema();
        unset($schema['name']);
    }

    /** @test */
    function array_accessor_blocks_unset()
    {
        $this->expectException(\Exception::class);

        $schema = new MySchema();
        $schema->offsetUnset('name');
    }
}

class MySchema extends Schema
{
    protected function definition()
    {
        return ['name' => ['type' => 'string']];
    }
}
