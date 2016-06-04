<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ApiExceptionTest
 *
 * @package  Arcanedev\Stripe\Tests\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApiExceptionTest extends StripeTestCase
{
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
                'Stripe error message',
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
            $this->assertSame('Stripe error message', $e->getMessage());
            $this->assertSame(500, $e->getCode());
            $this->assertSame(500, $e->getHttpStatus());
            $this->assertSame('api_error', $e->getType());
            $this->assertSame('api_error_code', $e->getStripeCode());
            $this->assertSame("{'foo':'bar'}", $e->getHttpBody());
            $this->assertSame(['foo' => 'bar'], $e->getJsonBody());
            $this->assertSame(['param'=> 'some-id'], $e->getParams());
        }
    }
}
