<?php

namespace Plasm\Tests\Fixtures;

trait TestIntegration
{
    protected function initIntegration()
    {
        if (empty($this->integratesFoo)) {
            throw new \Exception('You need to define $integratesFoo class in your Changeset.');
        }
    }
}
