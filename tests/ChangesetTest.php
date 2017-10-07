<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;
use Plasm\Changeset;
use Plasm\Tests\Fixtures\EmptyChangeset;
use Plasm\Tests\Fixtures\EmptySchema;
use Plasm\Tests\Fixtures\TestChangeset;
use Plasm\Tests\Fixtures\TestSchema;

final class ChangesetTest extends TestCase
{
    private $validAttrs = [
        'name' => 'Joe Frank',
        'email' => 'joe@test.com',
        'is_admin' => '1',
        'age' => '24',
        'money' => '18.75',
    ];

    // Instantiating

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

    // Casts

    /** @test */
    function changeset_casts_string()
    {
        $changeset = TestChangeset::using(TestSchema::class)->change($this->validAttrs);

        $this->assertTrue(
            is_string($changeset->getChange('name'))
        );
    }

    /** @test */
    function changeset_casts_integer()
    {
        $changeset = TestChangeset::using(TestSchema::class)->change($this->validAttrs);

        $this->assertTrue(
            is_integer($changeset->getChange('age'))
        );
    }

    /** @test */
    function changeset_casts_boolean()
    {
        $changeset = TestChangeset::using(TestSchema::class)->change($this->validAttrs);

        $this->assertTrue(
            is_bool($changeset->getChange('is_admin'))
        );
    }

    /** @test */
    function changeset_casts_float()
    {
        $changeset = TestChangeset::using(TestSchema::class)->change($this->validAttrs);

        $this->assertTrue(
            is_float($changeset->getChange('money'))
        );
    }

    /** @test */
    function changeset_casts_default()
    {
        $attrs = $this->validAttrs;
        unset($attrs['is_admin']);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse(isset($attrs['is_admin']));
        $this->assertTrue(
            $changeset->getChange('is_admin') === false
        );
    }

    /** @test */
    function changeset_cast_filters_unspecified_fields()
    {
        $attrs = $this->validAttrs;
        $attrs['foobar'] = 'baz';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse(
            isset($changeset->changes()['foobar'])
        );
        $this->assertTrue(
            is_null($changeset->getChange('foobar'))
        );
    }
}
