<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Exceptions\InvalidRequestErrorException;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTest;

class InvalidRequestErrorTest extends StripeTest
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
    public function testInvalidObject()
    {
        try {
            Customer::retrieve('invalid');
        }
        catch (InvalidRequestErrorException $e) {
            $this->assertEquals(404, $e->getCode());
            $this->assertEquals(404, $e->getHttpStatus());
        }
    }

    public function testBadData()
    {
        try {
            Charge::create();
        }
        catch (InvalidRequestErrorException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertEquals(400, $e->getHttpStatus());
        }
    }
}
