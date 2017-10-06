<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;

use Plasm\Tests\Fixtures\EmptyChangeset;
use Plasm\Tests\Fixtures\EmptySchema;
use Plasm\Tests\Fixtures\TestChangeset;
use Plasm\Tests\Fixtures\TestSchema;

/**
 * @covers \Plasm\Changeset::cast
 */
final class ChangesetCastsTest extends TestCase
{
    private $validAttrs = [
        'name' => 'Joe Frank',
        'email' => 'joe@test.com',
        'is_admin' => '1',
        'age' => '24',
        'money' => '18.75',
    ];

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
