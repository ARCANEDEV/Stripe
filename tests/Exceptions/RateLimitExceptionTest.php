<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Contracts\Utilities\Request\HttpClientInterface;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Tests\StripeTestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class     RateLimitExceptionTest
 *
 * @package  Arcanedev\Stripe\Tests\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RateLimitExceptionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->mock = null;
        $this->call = 0;
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\RateLimitException
     * @expectedExceptionCode 429
     */
    public function testRateLimit()
    {
        $return = [
            'error' => [],
        ];

        $this->mockRequest('GET', '/v1/accounts/acct_DEF', [], $return, 429);
        Account::retrieve('acct_DEF');

        $this->fail('Did not raise an error.');
    }
}
