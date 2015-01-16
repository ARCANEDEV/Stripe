<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Utilities\ApiErrorsHandler;

use Arcanedev\Stripe\Tests\StripeTestCase;

class ApiErrorsHandlerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var ApiErrorsHandler */
    private $errorHandler;
    const API_ERRORS_HANDLER_CLASS = 'Arcanedev\\Stripe\\Utilities\\ApiErrorsHandler';

    // Exceptions classes
    const API_EXCEPTION_CLASS             = 'Arcanedev\\Stripe\\Exceptions\\ApiException';
    const AUTHENTICATION_EXCEPTION_CLASS  = 'Arcanedev\\Stripe\\Exceptions\\AuthenticationException';
    const CARD_EXCEPTION_CLASS            = 'Arcanedev\\Stripe\\Exceptions\\CardException';
    const INVALID_REQUEST_EXCEPTION_CLASS = 'Arcanedev\\Stripe\\Exceptions\\InvalidRequestException';
    const RATE_LIMIT_EXCEPTION_CLASS      = 'Arcanedev\\Stripe\\Exceptions\\RateLimitException';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->errorHandler = new ApiErrorsHandler;
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
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(self::API_ERRORS_HANDLER_CLASS, $this->errorHandler);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode    500
     * @expectedExceptionMessage API Error Message
     */
    public function testMustThrowApiExceptionOnNullResponse()
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
    public function testCanHandleApiException()
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
    public function testCanHandleRateLimitException()
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
    public function testCanHandleAuthenticationException()
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
    public function testCanHandleCardException()
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
    public function testCanHandleInvalidRequestException()
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
