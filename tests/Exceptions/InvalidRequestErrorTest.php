<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;

class InvalidRequestErrorTest extends StripeTestCase
{
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
     * @expectedException        \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode    404
     * @expectedExceptionMessage No such customer: invalid
     */
    public function it_must_throw_invalid_request_exception_on_invalid_params()
    {
        Customer::retrieve('invalid');
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode    400
     * @expectedExceptionMessage Must provide source or customer.
     */
    public function it_must_throw_invalid_request_exception_on_bad_data()
    {
        Charge::create();
    }
}
