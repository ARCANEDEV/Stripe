<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Exceptions;
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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Stripe\Utilities\ErrorsHandler */
    private $errorHandler;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
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

        $this->assertTrue(true);
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

    /** @test */
    public function it_can_handle_authentication_exception_bis()
    {
        $response = [
            'error' => [
                'type'    => 'invalid_request_error',
                'message' => 'You did not provide an API key.',
            ],
        ];

        try {
            $this->errorHandler->handle(json_encode($response), 401, [], $response);

            $this->fail('Did not raise error');
        }
        catch (Exceptions\AuthenticationException $e) {
            $this->assertSame('You did not provide an API key.', $e->getMessage());
        }
        catch (\Exception $e) {
            $this->fail("Unexpected exception: " . get_class($e));
        }
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

    /** @test */
    public function it_can_handle_oauth_invalid_request_exception()
    {
        $response = [
            'error'             => 'invalid_request',
            'error_description' => 'No grant type specified',
        ];

        try {
            $this->errorHandler->handle(json_encode($response), 400, [], $response);
        }
        catch (Exceptions\OAuth\InvalidRequestException $e) {
            $this->assertSame($response['error'], $e->getErrorCode());
            $this->assertSame($response['error_description'], $e->getMessage());
        }
        catch (\Exception $e) {
            $this->fail('Unexpected exception: '.get_class($e));
        }
    }

    /** @test */
    public function it_can_handle_oauth_invalid_grant_exception()
    {
        $response = [
            'error'             => 'invalid_grant',
            'error_description' => 'This authorization code has already been used. All tokens issued with this code have been revoked.',
        ];

        try {
            $this->errorHandler->handle(json_encode($response), 400, [], $response);

            $this->fail('Did not raise error');
        }
        catch (Exceptions\OAuth\InvalidGrantException $e) {
            $this->assertSame($response['error'], $e->getErrorCode());
            $this->assertSame($response['error_description'], $e->getMessage());
        }
        catch (\Exception $e) {
            $this->fail('Unexpected exception: '.get_class($e));
        }
    }

    /** @test */
    public function it_can_handle_card_exception_bis()
    {
        $response = [
            'error' => [
                'type'         => 'card_error',
                'message'      => 'Your card was declined.',
                'code'         => 'card_declined',
                'decline_code' => 'generic_decline',
                'charge'       => 'ch_declined_charge',
            ],
        ];

        try {
            $this->errorHandler->handle(json_encode($response), 402, [], $response);

            $this->fail('Did not raise error');
        }
        catch (Exceptions\CardException $e) {
            $this->assertSame('Your card was declined.', $e->getMessage());
            $this->assertSame('card_declined', $e->getStripeCode());
            $this->assertSame('generic_decline', $e->getDeclineCode());
        }
        catch (\Exception $e) {
            $this->fail("Unexpected exception: " . get_class($e));
        }
    }
}
