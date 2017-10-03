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

    // accepted

    function test_validateAccepted_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['accept_tos'] = '1';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateAccepted_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['accept_tos'] = '0';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    // change

    function test_validateChange_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['banana_count'] = '3';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateChange_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['banana_count'] = '0';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    // confirmation

    function test_validateConfirmation_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['password'] = 'password123';
        $attrs['password_confirmation'] = 'password123';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateConfirmation_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['password'] = 'password123';
        $attrs['password_confirmation'] = '123password';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    // count TODO

    // exclusion

    function test_validateExclusion_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['foo'] = 'zing';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateExclusion_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['foo'] = 'bar';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    // format

    function test_validateFormat_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['email'] = 'joe@joe.joe';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateFormat_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['email'] = 'joe joe';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    // inclusion

    function test_validateInclusion_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['bar'] = 'x';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateInclusion_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['bar'] = 'z';

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    // length TODO
    // number TODO

    // required

    function test_validateRequired_invalid()
    {
        $attrs = $this->validAttrs;
        unset($attrs['name']);

        $changeset = new TestChangeset(TestSchema::class, $attrs);
        $this->assertFalse($changeset->valid());
    }

    // subset TODO
}
