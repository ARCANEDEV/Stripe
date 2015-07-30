<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class ApiExceptionTest
 * @package Arcanedev\Stripe\Tests\Exceptions
 */
class ApiExceptionTest extends StripeTestCase
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
    /** @test */
    public function it_can_be_thrown()
    {
        try {
            throw new ApiException(
                // Message
                "Stripe error message",
                // Status Code
                500,
                // Stripe error type
                'api_error',
                // Stripe error code
                'api_error_code',
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
            $this->assertEquals('api_error', $e->getType());
            $this->assertEquals('api_error_code', $e->getStripeCode());
            $this->assertEquals("{'foo':'bar'}", $e->getHttpBody());
            $this->assertEquals(['foo' => 'bar'], $e->getJsonBody());
            $this->assertEquals(['param'=> 'some-id'], $e->getParams());
        }
    }
}
