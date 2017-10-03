<?php

namespace Plasm\Tests\Fixtures;

use Plasm\Changeset;

class TestChangeset extends Changeset
{
    /**
     * Do the things.
     *
     * @return $this
     */
    public function change()
    {
        $this
            ->cast([
                'name', 'email', 'is_admin', 'age', 'money', 'password',
                'password_confirmation', 'foo', 'bar', 'accept_tos',
                'banana_count',
            ])
            ->validateAcceptance('accept_tos')
            ->validateChange('banana_count', $this->validateHasMoreThanTwo())
            ->validateConfirmation('password')
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
