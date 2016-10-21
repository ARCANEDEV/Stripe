<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\ErrorsHandler;

/**
 * Class     ErrorsHandlerTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ErrorsHandlerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Utilities\ErrorsHandler */
    private $errorHandler;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->errorHandler = new ErrorsHandler;
    }

    public function tearDown()
    {
        unset($this->errorHandler);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ErrorsHandler::class, $this->errorHandler);
    }

    /** @test */
    public function it_must_skip_handling_if_status_code_is_ok()
    {
        $this->errorHandler->handle('{"error":{"message":"Error"}}', 202, [], null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode     500
     * @expectedExceptionMessage  Invalid response object from API: {"error":{"message":"Error"}} (HTTP response code was 500)
     */
    public function it_must_throw_api_exception_on_null_response()
    {
        $this->errorHandler->handle('{"error":{"message":"Error"}}', 500, [], null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode     500
     * @expectedExceptionMessage  API Error Message
     */
    public function it_can_handle_api_exception()
    {
        $this->errorHandler->handle('{"error":{"message":"Error"}}', 500, [], [
            'error' => [
                'message' => 'API Error Message'
            ],
        ]);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\RateLimitException
     * @expectedExceptionCode     429
     * @expectedExceptionMessage  Rate Limit Error
     */
    public function it_can_handle_rate_limit_exception()
    {
        $this->errorHandler->handle('{"error":{"message":"Rate Limit Error"}}', 400, [], [
            'error' => [
                'message' => 'Rate Limit Error',
                'code'    => 'rate_limit',
            ],
        ]);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\AuthenticationException
     * @expectedExceptionCode     401
     * @expectedExceptionMessage  Authentication Error
     */
    public function it_can_handle_authentication_exception()
    {
        $this->errorHandler->handle('{"error":{"message":"Authentication Error"}}', 401, [], [
            'error' => [
                'message' => 'Authentication Error',
            ],
        ]);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\CardException
     * @expectedExceptionCode     402
     * @expectedExceptionMessage  Card Error
     */
    public function it_can_handle_card_exception()
    {
        $this->errorHandler->handle('{"error":{"message":"Card Error"}}', 402, [], [
            'error' => [
                'message' => 'Card Error',
            ],
        ]);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode     404
     * @expectedExceptionMessage  Invalid Request Error
     */
    public function it_can_handle_invalid_request_exception()
    {
        $this->errorHandler->handle('{"error":{"message":"Invalid Request Error"}}', 404, [], [
            'error' => [
                'message' => 'Invalid Request Error',
                'type'    => 'invalid_request_error',
            ],
        ]);
    }
}
