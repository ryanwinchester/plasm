<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;

use Plasm\Tests\Fixtures\EmptyChangeset;
use Plasm\Tests\Fixtures\EmptySchema;
use Plasm\Tests\Fixtures\TestChangeset;
use Plasm\Tests\Fixtures\TestSchema;

final class ChangesetValidationsTest extends TestCase
{
    private $validAttrs = [
        'name' => 'Joe Frank',
        'email' => 'joe@test.com',
        'is_admin' => '1',
        'age' => '24',
        'money' => '18.75',
    ];

    /** @test */
    function changeset_empty_attrs()
    {
        $changeset = EmptyChangeset::using(EmptySchema::class)->change([]);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function changeset_valid_attrs()
    {
        $changeset = TestChangeset::using(TestSchema::class)->change($this->validAttrs);

        $this->assertTrue($changeset->valid());
    }

    // accepted

    /** @test */
    function validateAccepted_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['accept_tos'] = '1';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function validateAccepted_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['accept_tos'] = '0';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    // change

    /** @test */
    function validateChange_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['banana_count'] = '3';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function validateChange_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['banana_count'] = '0';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    /** @test */
    function validateChange_invalid_with_message()
    {
        $attrs = $this->validAttrs;
        $attrs['banana_count'] = '4';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
        $this->assertEquals(
            'You have to have less than 4, man',
            $changeset->getErrors('banana_count')[0]
        );
    }

    // confirmation

    /** @test */
    function validateConfirmation_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['password'] = 'password123';
        $attrs['password_confirmation'] = 'password123';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function validateConfirmation_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['password'] = 'password123';
        $attrs['password_confirmation'] = '123password';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    // count

    /**
     * @test
     * @dataProvider validCountProvider
     */
    function validateCount_valid($attrs)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /**
     * @test
     * @dataProvider invalidCountProvider
     */
    function validateCount_invalid($attrs, $errors)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
        $this->assertEquals($errors, $changeset->errors());
    }

    // exclusion

    /** @test */
    function validateExclusion_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['foo'] = 'zing';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function validateExclusion_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['foo'] = 'bar';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    // format

    /** @test */
    function validateFormat_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['email'] = 'joe@joe.joe';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function validateFormat_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['email'] = 'joe joe';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    // inclusion

    /** @test */
    function validateInclusion_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['bar'] = 'x';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function validateInclusion_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['bar'] = 'z';

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    // length

    /**
     * @test
     * @dataProvider validLengthProvider
     */
    function validateLength_valid($attrs)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /**
     * @test
     * @dataProvider invalidLengthProvider
     */
    function validateLength_invalid($attrs, $errors)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
        $this->assertEquals($errors, $changeset->errors());
    }

    // number

    /**
     * @test
     * @dataProvider validNumberProvider
     */
    function validateNumber_valid($attrs)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /**
     * @test
     * @dataProvider invalidNumberProvider
     */
    function validateNumber_invalid($attrs, $errors)
    {
        $attrs = array_merge($this->validAttrs, $attrs);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
        $this->assertEquals($errors, $changeset->errors());
    }

    // required

    /** @test */
    function validateRequired_invalid()
    {
        $attrs = $this->validAttrs;
        unset($attrs['name']);

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    // subset

    /** @test */
    function validateSubset_valid()
    {
        $attrs = $this->validAttrs;
        $attrs['things'] = [2, 3, 4];

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertTrue($changeset->valid());
    }

    /** @test */
    function validateSubset_invalid()
    {
        $attrs = $this->validAttrs;
        $attrs['things'] = [4, 5, 6, 7];

        $changeset = TestChangeset::using(TestSchema::class)->change($attrs);

        $this->assertFalse($changeset->valid());
    }

    function validCountProvider()
    {
        return [
            'test valid count:min rule' => [['skill' => ['php']]],
            'test valid count:max rule' => [['skill' => ['php', 'mysql', 'redis']]],
            'test valid count:is rule'  => [['topic' => ['oop', 'design patterns']]],
        ];
    }

    function invalidCountProvider()
    {
        return [
            'test invalid count:min rule' => [
                ['skill' => []],
                ['skill' => ['you need at least 1 Skills']],
            ],
            'test invalid count:max rule' => [
                ['skill' => ['php', 'mysql', 'redis', 'erlang']],
                ['skill' => ['you can have, at most 3, Skills']],
            ],
            'test invalid count:is and count:min rules'  => [
                ['topic' => ['oop']],
                ['topic' => ['you do not have 2 Topics', 'you need at least 2 Topics']],
            ],
            'test invalid count:is and count:max rules'  => [
                ['topic' => ['oop', 'design patterns', 'functional programming']],
                ['topic' => ['you do not have 2 Topics', 'you can have, at most 2, Topics']],
            ],
        ];
    }

    function validLengthProvider()
    {
        return [
            'test valid length:min rule' => [['name' => 'Ed']],
            'test valid length:max rule' => [['name' => 'Pablo Diego José']],
            'test valid length:is rule'  => [['password_hash' => md5('password')]],
        ];
    }

    function invalidLengthProvider()
    {
        return [
            'test invalid length:min rule' => [
                ['name' => 'Y'],
                ['name' => ['you need at least 2 Names']],
            ],
            'test invalid length:max rule' => [
                ['name' => 'Wolfeschlegelsteinhausenbergerdorff'],
                ['name' => ['you can have, at most 16, Names']],
            ],
            'test invalid length:is rule'  => [
                ['password_hash' => sha1('password')],
                ['password_hash' => ['you do not have 32 Password hashs']],
            ],
        ];
    }

    function validNumberProvider()
    {
        return [
            'test valid number:less_than rule' => [['money' => 999]],
            'test valid number:greater_than rule' => [['money' => 1.01]],
            'test valid number:less_than_or_equal_to rule' => [['age' => 60]],
            'test valid number:greater_than_or_equal_to rule' => [['age' => 18]],
            'test valid number:equal_to rule' => [['lucky' => 7]],
        ];
    }

    function invalidNumberProvider()
    {
        return [
            'test invalid number:less_than rule' => [
                ['money' => 1000],
                ['money' => ['Money should be less than 1000']],
            ],
            'test invalid number:greater_than rule' => [
                ['money' => 1],
                ['money' => ['Money should be greater than 1']],
            ],
            'test invalid number:less_than_or_equal_to rule' => [
                ['age' => 61],
                ['age' => ['Age should be less than or equal to 60']],
            ],
            'test invalid number:greater_than_or_equal_to rule' => [
                ['age' => 17],
                ['age' => ['Age should be greater than or equal to 18']],
            ],
            'test invalid number:equal_to rule' => [
                ['lucky' => 13],
                ['lucky' => ['Lucky should be equal to 7']],
            ],
        ];
    }
}
