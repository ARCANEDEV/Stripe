<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\ErrorsHandler;

/**
 * Class ErrorsHandlerTest
 * @package Arcanedev\Stripe\Tests\Utilities
 */
class ErrorsHandlerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const API_ERRORS_HANDLER_CLASS        = 'Arcanedev\\Stripe\\Utilities\\ErrorsHandler';

    // Exceptions classes
    const API_EXCEPTION_CLASS             = 'Arcanedev\\Stripe\\Exceptions\\ApiException';
    const AUTHENTICATION_EXCEPTION_CLASS  = 'Arcanedev\\Stripe\\Exceptions\\AuthenticationException';
    const CARD_EXCEPTION_CLASS            = 'Arcanedev\\Stripe\\Exceptions\\CardException';
    const INVALID_REQUEST_EXCEPTION_CLASS = 'Arcanedev\\Stripe\\Exceptions\\InvalidRequestException';
    const RATE_LIMIT_EXCEPTION_CLASS      = 'Arcanedev\\Stripe\\Exceptions\\RateLimitException';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var ErrorsHandler */
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
        parent::tearDown();

        unset($this->errorHandler);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::API_ERRORS_HANDLER_CLASS, $this->errorHandler);
    }

    /** @test */
    public function it_must_skip_handling_if_status_code_is_ok()
    {
        $respBody = '{"error":{"message":"Error"}}';
        $respCode = 202;
        $response = null;

        $this->errorHandler->handle($respBody, $respCode, $response);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode    500
     * @expectedExceptionMessage API Error Message
     */
    public function it_must_throw_api_exception_on_null_response()
    {
        $respBody = '{"error":{"message":"Error"}}';
        $respCode = 500;
        $response = null;

        $this->setExpectedException(
            self::API_EXCEPTION_CLASS,
            "Invalid response object from API: $respBody (HTTP response code was $respCode)",
            $respCode
        );
        $this->errorHandler->handle($respBody, $respCode, $response);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode    500
     * @expectedExceptionMessage API Error Message
     */
    public function it_can_handle_api_exception()
    {
        $respBody = '{"error":{"message":"Error"}}';
        $respCode = 500;
        $response = [
            'error' => [
                'message' => 'API Error Message'
            ],
        ];

        $this->errorHandler->handle($respBody, $respCode, $response);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\RateLimitException
     * @expectedExceptionCode    429
     * @expectedExceptionMessage Rate Limit Error
     */
    public function it_can_handle_rate_limit_exception()
    {
        $respBody = '{"error":{"message":"Rate Limit Error"}}';
        $respCode = 400;
        $response = [
            'error' => [
                'message' => 'Rate Limit Error',
                'code'    => 'rate_limit',
            ],
        ];

        $this->errorHandler->handle($respBody, $respCode, $response);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\AuthenticationException
     * @expectedExceptionCode    401
     * @expectedExceptionMessage Authentication Error
     */
    public function it_can_handle_authentication_exception()
    {
        $respBody = '{"error":{"message":"Authentication Error"}}';
        $respCode = 401;
        $response = [
            'error' => [
                'message' => 'Authentication Error',
            ],
        ];

        $this->setExpectedException(
            self::AUTHENTICATION_EXCEPTION_CLASS,
            'Authentication Error',
            $respCode
        );

        $this->errorHandler->handle($respBody, $respCode, $response);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\CardException
     * @expectedExceptionCode    402
     * @expectedExceptionMessage Card Error
     */
    public function it_can_handle_card_exception()
    {
        $respBody = '{"error":{"message":"Card Error"}}';
        $respCode = 402;
        $response = [
            'error' => [
                'message' => 'Card Error',
            ],
        ];

        $this->errorHandler->handle($respBody, $respCode, $response);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode    404
     * @expectedExceptionMessage Invalid Request Error
     */
    public function it_can_handle_invalid_request_exception()
    {
        $respBody = '{"error":{"message":"Invalid Request Error"}}';
        $respCode = 404;
        $response = [
            'error' => [
                'message' => 'Invalid Request Error',
                'type'    => 'invalid_request_error',
            ],
        ];

        $this->errorHandler->handle($respBody, $respCode, $response);
    }
}
