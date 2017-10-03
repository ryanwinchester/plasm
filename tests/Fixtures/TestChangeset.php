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
                'name', 'email', 'is_admin', 'age', 'money', 'password', 'nothing',
                'accept_tos',
            ])
            ->validateFormat('email', '/^.+@.+\..+$/')
            ->validateAcceptance('accept_tos')
            ->validateRequired('name', 'email', 'password');
    }
}
