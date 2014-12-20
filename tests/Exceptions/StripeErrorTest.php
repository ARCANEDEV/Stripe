<?php


namespace Arcanedev\Stripe\Tests\Exceptions;


use Arcanedev\Stripe\Exceptions\StripeError;

class StripeErrorTest extends \PHPUnit_Framework_TestCase {
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
    public function testCreation()
    {
        try {
            throw new StripeError(
                "Stripe error message", // Message
                500,                    // Status Code
                null,                   // Stripe error type
                null,                   // Stripe error code
                "{'foo':'bar'}",        // Body json
                ['foo'  => 'bar'],      // Body array
                ['param'=> 'some-id']   // Params
            );
        }
        catch (StripeError $e) {
            $this->assertEquals("Stripe error message", $e->getMessage());
            $this->assertEquals(500, $e->getCode());
            $this->assertEquals(500, $e->getHttpStatus());
            $this->assertEquals("{'foo':'bar'}", $e->getHttpBody());
            $this->assertEquals(['foo' => 'bar'], $e->getJsonBody());
            $this->assertEquals(['param'=> 'some-id'], $e->getParams());
        }
    }
}
