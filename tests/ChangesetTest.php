<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;
use Plasm\Changeset;
use Plasm\Tests\Fixtures\EmptyChangeset;
use Plasm\Tests\Fixtures\EmptySchema;

/**
 * @covers \Plasm\Changeset
 */
final class ChangesetTest extends TestCase
{
    /** @test */
    public function can_be_created_with_schema_instance()
    {
        $this->assertInstanceOf(
            Changeset::class,
            new EmptyChangeset(new EmptySchema())
        );
    }

    /** @test */
    public function can_be_created_with_schema_class_name()
    {
        $this->assertInstanceOf(
            Changeset::class,
            new EmptyChangeset(EmptySchema::class)
        );
    }

    /** @test */
    public function can_be_created_with_static_constructor()
    {
        $this->assertInstanceOf(
            Changeset::class,
            EmptyChangeset::using(EmptySchema::class)
        );
    }

    /** @test */
    public function can_not_be_created_with_invalid_schema()
    {
        $this->expectException(\TypeError::class);

        new EmptyChangeset([]);
    }

    /** @test */
    public function can_not_be_statically_created_with_invalid_schema()
    {
        $this->expectException(\TypeError::class);

        EmptyChangeset::using([]);
    }
}
