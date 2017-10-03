<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;
use Plasm\Tests\Fixtures\EmptyChangeset;
use Plasm\Tests\Fixtures\EmptySchema;
use Plasm\Tests\Fixtures\TestChangeset;
use Plasm\Tests\Fixtures\TestSchema;

/**
 * @covers \Plasm\ChangesetValidations
 */
final class ChangesetValidationsTest extends TestCase
{
    private $validAttrs = [
        'name' => 'Joe Frank',
        'email' => 'joe@test.com',
        'is_admin' => '1',
        'age' => '24',
        'money' => '18.75',
        'password' => 'password123',
        'accept_tos' => '1',
    ];

    function test_changeset_empty_attrs()
    {
        $changeset = new EmptyChangeset(EmptySchema::class, []);
        $this->assertTrue($changeset->valid());
    }

    function test_changeset_valid_attrs()
    {
        $changeset = new TestChangeset(TestSchema::class, $this->validAttrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateAccepted_valid()
    {
        $changeset = new TestChangeset(TestSchema::class, $this->validAttrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateAccepted_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['accept_tos'] = '0';

        $changeset = new TestChangeset(TestSchema::class, $this->validAttrs);
        $this->assertFalse($changeset->valid());
    }

    function test_validateAccepted_not_included()
    {
        $attrs = $this->validAttrs;
        unset($attrs['accept_tos']);

        $changeset = new TestChangeset(TestSchema::class, $this->validAttrs);
        $this->assertFalse($changeset->valid());
    }

    function test_validateRequired_invalid()
    {
        $attrs = $this->validAttrs;
        unset($attrs['name']);

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    function test_validateFormat_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['email'] = 'joe joe';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }
}
