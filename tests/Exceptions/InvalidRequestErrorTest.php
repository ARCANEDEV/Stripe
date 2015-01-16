<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Exceptions\InvalidRequestException;

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
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionMessage No such customer: invalid
     * @expectedExceptionCode 404
     */
    public function testInvalidObject()
    {
        Customer::retrieve('invalid');
    }

    /**
     * @test
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionMessage You must supply either a card or a customer id
     * @expectedExceptionCode 400
     */
    public function testBadData()
    {
        Charge::create();
    }
}
