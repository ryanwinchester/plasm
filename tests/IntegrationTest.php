<?php

namespace Plasm\Tests;

use PHPUnit\Framework\TestCase;
use Plasm\Changeset;
use Plasm\Tests\Fixtures\TestIntegration;
use Plasm\Tests\Fixtures\TestSchema;

class IntegrationTest extends TestCase
{
    /** @test */
    function good_integration()
    {
        new GoodChangeset(TestSchema::class);
    }

    /** @test */
    function bad_integration()
    {
        $this->expectException(\Exception::class);

        new BadChangeset(TestSchema::class);
    }
}

class GoodChangeset extends Changeset
{
    use TestIntegration;

    protected $integratesFoo = 'foo';
}

class BadChangeset extends Changeset
{
    use TestIntegration;
}
