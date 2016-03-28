<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     InvalidRequestErrorTest
 *
 * @package  Arcanedev\Stripe\Tests\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class InvalidRequestErrorTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode     404
     * @expectedExceptionMessage  No such customer: invalid
     */
    public function it_must_throw_invalid_request_exception_on_invalid_params()
    {
        Customer::retrieve('invalid');
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode     400
     * @expectedExceptionMessage  Must provide source or customer.
     */
    public function it_must_throw_invalid_request_exception_on_bad_data()
    {
        Charge::create();
    }
}
