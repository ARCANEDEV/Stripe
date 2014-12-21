<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Tests\TestCase;

class ApiExceptionTest extends TestCase
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
    public function testCreation()
    {
        try {
            throw new ApiException(
                // Message
                "Stripe error message",
                // Status Code
                500,
                // Stripe error type
                null,
                // Stripe error code
                null,
                // Response Body json
                "{'foo':'bar'}",
                // Response Body array
                ['foo'  => 'bar'],
                // Params
                ['param'=> 'some-id']
            );
        }
        catch (ApiException $e) {
            $this->assertEquals("Stripe error message", $e->getMessage());
            $this->assertEquals(500, $e->getCode());
            $this->assertEquals(500, $e->getHttpStatus());
            $this->assertEquals("{'foo':'bar'}", $e->getHttpBody());
            $this->assertEquals(['foo' => 'bar'], $e->getJsonBody());
            $this->assertEquals(['param'=> 'some-id'], $e->getParams());
        }
    }
}
