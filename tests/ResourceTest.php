<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Tests\Dummies\ResourceObject;

/**
 * Class     ResourceTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ResourceTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function it_must_throw_invalid_argument_exception_on_parameters()
    {
        ResourceObject::all('parameters');
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_on_api_key()
    {
        ResourceObject::all(['param' => 'value'], true);
    }
}
