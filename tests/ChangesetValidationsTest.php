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
        $changeset = EmptyChangeset::using(EmptySchema::class)->change([]);
        $this->assertTrue($changeset->valid());
    }

    function test_changeset_valid_attrs()
    {
        $changeset = TestChangeset::using(TestSchema::class)->change($this->validAttrs);
        $this->assertTrue($changeset->valid());
    }

    // accepted

    function test_validateAccepted_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['accept_tos'] = '1';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateAccepted_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['accept_tos'] = '0';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
    }

    // change

    function test_validateChange_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['banana_count'] = '3';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateChange_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['banana_count'] = '0';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
    }

    // confirmation

    function test_validateConfirmation_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['password'] = 'password123';
        $attrs['password_confirmation'] = 'password123';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateConfirmation_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['password'] = 'password123';
        $attrs['password_confirmation'] = '123password';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
    }

    // count

    /**
     * @dataProvider validCountProvider
     */
    public function test_validateCount_valid($attrs)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertTrue($changeset->valid());
    }

    /**
     * @dataProvider invalidCountProvider
     */
    public function test_validateCount_invalid($attrs, $errors)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
        $this->assertEquals($errors, $changeset->errors());
    }

    // exclusion

    function test_validateExclusion_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['foo'] = 'zing';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateExclusion_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['foo'] = 'bar';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
    }

    // format

    function test_validateFormat_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['email'] = 'joe@joe.joe';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateFormat_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['email'] = 'joe joe';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
    }

    // inclusion

    function test_validateInclusion_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['bar'] = 'x';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertTrue($changeset->valid());
    }

    function test_validateInclusion_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['bar'] = 'z';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
    }

    // length TODO

    function test_validateLength_valid()
    {
        // $attrs = $this->validAttrs;
        //
        // $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        // $this->assertTrue($changeset->valid());

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    function test_validateLength_invalid()
    {
        // $attrs = $this->validAttrs;
        //
        // $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        // $this->assertFalse($changeset->valid());

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    // number TODO

    function test_validateNumber_valid()
    {
        // $attrs = $this->validAttrs;
        //
        // $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        // $this->assertTrue($changeset->valid());

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    function test_validateNumber_invalid()
    {
        // $attrs = $this->validAttrs;
        //
        // $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        // $this->assertFalse($changeset->valid());

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    // required

    function test_validateRequired_invalid()
    {
        $attrs = $this->validAttrs;
        unset($attrs['name']);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        $this->assertFalse($changeset->valid());
    }

    // subset TODO

    function test_validateSubset_valid()
    {
        // $attrs = $this->validAttrs;
        //
        // $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        // $this->assertTrue($changeset->valid());

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    function test_validateSubset_invalid()
    {
        // $attrs = $this->validAttrs;
        //
        // $changeset = TestChangeset::using(TestSchema::class)->change($attrs);
        // $this->assertFalse($changeset->valid());

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function validCountProvider()
    {
        return [
            'test valid min rule' => [['skill' => ['php']]],
            'test valid max rule' => [['skill' => ['php', 'mysql', 'redis']]],
            'test valid is rule'  => [['topic' => ['oop', 'design patterns']]],
        ];
    }

    public function invalidCountProvider()
    {
        return [
            'test invalid min rule' => [
                ['skill' => []],
                ['skill' => ['you need at least 1 Skills']],
            ],
            'test invalid max rule' => [
                ['skill' => ['php', 'mysql', 'redis', 'erlang']],
                ['skill' => ['you can have, at most 3, Skills']],
            ],
            'test invalid is and min rules'  => [
                ['topic' => ['oop']],
                ['topic' => ['you do not have 2 Topics', 'you need at least 2 Topics']],
            ],
            'test invalid is and max rules'  => [
                ['topic' => ['oop', 'design patterns', 'functional programming']],
                ['topic' => ['you do not have 2 Topics', 'you can have, at most 2, Topics']],
            ],
        ];
    }
}
