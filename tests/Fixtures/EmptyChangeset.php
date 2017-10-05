<?php

namespace Plasm\Tests\Fixtures;

use Plasm\Changeset;

class EmptyChangeset extends Changeset
{
    function change($attrs) {
        return $this->cast($attrs, []);
    }
}
