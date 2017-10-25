<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Exceptions\OAuth\InvalidGrantException;
use Arcanedev\Stripe\Exceptions\OAuth\InvalidRequestException;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\StripeOAuth;

/**
 * Class     StripeOAuthTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class StripeOAuthTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /** @before */
    public function setUpClientId()
    {
        Stripe::setClientId('ca_test');
    }

    /** @after */
    public function tearDownClientId()
    {
        Stripe::setClientId(null);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_authorize_url()
    {
        $uriStr = StripeOAuth::authorizeUrl([
            'scope'       => 'read_write',
            'state'       => 'csrf_token',
            'stripe_user' => [
                'email'   => 'test@example.com',
                'url'     => 'https://example.com/profile/test',
                'country' => 'US',
            ],
        ]);

        $uri = parse_url($uriStr);
        parse_str($uri['query'], $params);

        $this->assertSame('https', $uri['scheme']);
        $this->assertSame('connect.stripe.com', $uri['host']);
        $this->assertSame('/oauth/authorize', $uri['path']);
        $this->assertSame('ca_test', $params['client_id']);
        $this->assertSame('read_write', $params['scope']);
        $this->assertSame('test@example.com', $params['stripe_user']['email']);
        $this->assertSame('https://example.com/profile/test', $params['stripe_user']['url']);
        $this->assertSame('US', $params['stripe_user']['country']);
    }

    /** @test */
    public function it_can_get_Token()
    {
        $this->mockRequest(
            'POST',
            '/oauth/token',
            [
                'grant_type' => 'authorization_code',
                'code'       => 'this_is_an_authorization_code',
            ],
            [
                'access_token'           => 'sk_access_token',
                'scope'                  => 'read_only',
                'livemode'               => false,
                'token_type'             => 'bearer',
                'refresh_token'          => 'sk_refresh_token',
                'stripe_user_id'         => 'acct_test',
                'stripe_publishable_key' => 'pk_test',
            ],
            200,
            Stripe::$connectBase
        );

        $response = StripeOAuth::token([
            'grant_type' => 'authorization_code',
            'code'       => 'this_is_an_authorization_code',
        ]);

        $this->assertSame('sk_access_token', $response->access_token);
    }

    /** @test */
    public function it_can_deauthorize()
    {
        $accountId = 'acct_test_deauth';

        $this->mockRequest(
            'POST',
            '/oauth/deauthorize',
            ['client_id' => 'ca_test', 'stripe_user_id' => $accountId],
            ['stripe_user_id' => $accountId],
            200,
            Stripe::$connectBase
        );

        $resp = StripeOAuth::deauthorize([
            'stripe_user_id' => $accountId,
        ]);

        $this->assertSame($accountId, $resp->stripe_user_id);
    }

    /** @test */
    public function it_can_handle_oauth_invalid_request_exception()
    {
        $this->mockRequest(
            'POST',
            '/oauth/token',
            [],
            [
                'error'             => 'invalid_request',
                'error_description' => 'No grant type specified',
            ],
            400,
            Stripe::$connectBase
        );

        try {
            StripeOAuth::token();

            $this->fail('Did not raise error');
        }
        catch (\Arcanedev\Stripe\Exceptions\OAuth\InvalidRequestException $e) {
            $this->assertSame('invalid_request', $e->getErrorCode());
            $this->assertSame('No grant type specified', $e->getMessage());
        }
        catch (\Exception $e) {
            $this->fail('Unexpected exception: '.get_class($e));
        }
    }

    /** @test */
    public function it_can_handle_oauth_invalid_grant_exception()
    {
        $this->mockRequest(
            'POST',
            '/oauth/token',
            [],
            [
                'error' => 'invalid_grant',
                'error_description' => 'This authorization code has already been used. All tokens issued with this code have been revoked.',
            ],
            400,
            Stripe::$connectBase
        );

        try {
            StripeOAuth::token();

            $this->fail('Did not raise error');
        }
        catch (\Arcanedev\Stripe\Exceptions\OAuth\InvalidGrantException $e) {
            $this->assertSame('invalid_grant', $e->getErrorCode());
            $this->assertSame('This authorization code has already been used. All tokens issued with this code have been revoked.', $e->getMessage());
        }
        catch (\Exception $e) {
            $this->fail('Unexpected exception: '.get_class($e));
        }
    }
}
