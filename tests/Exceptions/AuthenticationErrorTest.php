<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Stripe;

use Arcanedev\Stripe\Tests\StripeTestCase;

class AuthenticationErrorTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\AuthenticationException
     * @expectedExceptionCode 401
     */
    public function testInvalidCredentials()
    {
        Stripe::setApiKey('invalid');

        Customer::create();
    }
}
