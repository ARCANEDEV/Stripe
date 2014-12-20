<?php namespace Arcanedev\Stripe\Tests\Resources;


use Arcanedev\Stripe\Resources\BalanceTransaction;
use Arcanedev\Stripe\Tests\StripeTest;

class BalanceTransactionTest extends StripeTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

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
    public function testList()
    {
        $balanceTransactions  = BalanceTransaction::all();
        $this->assertEquals($balanceTransactions->url, '/v1/balance/history');
    }
}
