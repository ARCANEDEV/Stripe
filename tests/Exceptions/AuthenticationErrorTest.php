<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Stripe;

use Arcanedev\Stripe\Tests\StripeTest;

class AuthenticationErrorTest extends StripeTest
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
