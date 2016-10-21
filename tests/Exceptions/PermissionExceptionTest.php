<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     PermissionExceptionTest
 *
 * @package  Arcanedev\Stripe\Tests\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PermissionExceptionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException      \Arcanedev\Stripe\Exceptions\PermissionException
     * @expectedExceptionCode  403
     */
    public function it_must_throw_Permission_exception()
    {
        $return = [
            'error' => [],
        ];

        $this->mockRequest('GET', '/v1/accounts/acct_DEF', [], $return, 403);

        Account::retrieve('acct_DEF');
    }
}
