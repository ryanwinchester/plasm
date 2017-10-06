<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;
use Plasm\Changeset;
use Plasm\Tests\Fixtures\EmptyChangeset;
use Plasm\Tests\Fixtures\EmptySchema;
use Plasm\Tests\Fixtures\TestChangeset;

/**
 * @covers \Plasm\Changeset
 */
final class ChangesetTest extends TestCase
{
    /** @test */
    function can_be_created_with_schema_instance()
    {
        $this->assertInstanceOf(
            Changeset::class,
            new EmptyChangeset(new EmptySchema())
        );
    }

    /** @test */
    function can_be_created_with_schema_class_name()
    {
        $this->assertInstanceOf(
            Changeset::class,
            new EmptyChangeset(EmptySchema::class)
        );
    }

    /** @test */
    function can_be_created_with_static_constructor()
    {
        $this->assertInstanceOf(
            Changeset::class,
            EmptyChangeset::using(EmptySchema::class)
        );
    }

    /** @test */
    function can_be_created_and_run_with_constructor()
    {
        $this->assertInstanceOf(
            Changeset::class,
            new TestChangeset(EmptySchema::class, 'change', [])
        );
    }

    /** @test */
    function can_be_created_with_schema_class_defined_in_changeset()
    {
        $this->assertInstanceOf(
            Changeset::class,
            new TestChangeset()
        );
    }

    /** @test */
    function cant_be_created_with_schema_class_defined_in_changeset()
    {
        $this->expectException(\InvalidArgumentException::class);
        new EmptyChangeset();
    }

    /** @test */
    function cant_be_created_with_invalid_schema()
    {
        $this->expectException(\TypeError::class);
        new EmptyChangeset([]);
    }

    /** @test */
    function cant_be_statically_created_with_invalid_schema()
    {
        $this->expectException(\TypeError::class);
        EmptyChangeset::using([]);
    }
}
