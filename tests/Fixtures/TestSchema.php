<?php

namespace Plasm\Tests\Fixtures;

use Plasm\Schema;

class TestSchema extends Schema
{
    /**
     * Define the fields and their types.
     *
     * @return array
     */
    protected function definition()
    {
        return [
            'name' => ['type' => 'string'],
            'email' => ['type' => 'string'],
            'is_admin' => ['type' => 'boolean', 'default' => false],
            'age' => ['type' => 'integer'],
            'money' => ['type' => 'float'],
            'accept_tos' => ['type' => 'boolean', 'default' => false],
            'password' => ['type' => 'string', 'virtual' => true],
            'password_confirmation' => ['type' => 'string', 'virtual' => true],
            'password_hash' => ['type' => 'string'],
            'nothing' => ['type' => 'string', 'default' => null]
        ];
    }
}
