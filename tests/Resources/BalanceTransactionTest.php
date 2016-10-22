<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BalanceTransaction;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     BalanceTransactionTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BalanceTransactionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_list_all()
    {
        $balanceTransactions  = BalanceTransaction::all();

        $this->assertTrue($balanceTransactions->isList());
        $this->assertSame($balanceTransactions->url, '/v1/balance/history');
    }

    /** @test */
    public function it_can_retrieve()
    {
        $bts  = BalanceTransaction::all();

        $btId = $bts->data[0]->id;
        $bt   = BalanceTransaction::retrieve($btId);

        $this->assertInstanceOf(BalanceTransaction::class, $bt);
        $this->assertSame($btId, $bt->id);
    }
}
