<?php

namespace Plasm\Tests\Fixtures;

use Plasm\Changeset;

class TestChangeset extends Changeset
{
    protected $schemaClass = TestSchema::class;

    /**
     * Do the things.
     *
     * @return $this
     */
    public function change($attrs)
    {
        return $this
            ->cast($attrs, [
                'name', 'email', 'is_admin', 'age', 'money', 'password',
                'password_confirmation', 'password_hash', 'skill', 'topic',
                'foo', 'bar', 'accept_tos', 'banana_count',
            ])
            ->validateAcceptance('accept_tos')
            ->validateChange('banana_count', $this->validateHasMoreThanTwo())
            ->validateConfirmation('password')
            ->validateLength('name', ['min' => 2, 'max' => 16])
            ->validateLength('password_hash', ['is' => 32])
            ->validateCount('skill', ['min' => 1, 'max' => 3])
            ->validateCount('topic', ['is' => 2, 'min' => 2, 'max' => 2])
            ->validateExclusion('foo', ['bar', 'baz'])
            ->validateFormat('email', '/^.+@.+\..+$/')
            ->validateInclusion('bar', ['x', 'y'])
            ->validateRequired('name', 'email');
    }

    /**
     * Validate if the field has more than two of something.
     *
     * @return \Closure
     */
    protected function validateHasMoreThanTwo()
    {
        return function($value) {
            return $value > 2;
        };
    }
}
