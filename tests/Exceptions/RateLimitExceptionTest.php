<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Tests\StripeTestCase;

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

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException      \Arcanedev\Stripe\Exceptions\RateLimitException
     * @expectedExceptionCode  429
     */
    public function testRateLimit()
    {
        $this->mockRequest('GET', '/v1/accounts/acct_DEF', [], [
            'error' => [],
        ], 429);

        Account::retrieve('acct_DEF');
    }
}
