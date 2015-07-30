<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BalanceTransaction;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class BalanceTransactionTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class BalanceTransactionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\BalanceTransaction';

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
    /** @test */
    public function it_can_list_all()
    {
        $balanceTransactions  = BalanceTransaction::all();

        $this->assertTrue($balanceTransactions->isList());
        $this->assertEquals($balanceTransactions->url, '/v1/balance/history');
    }

    /** @test */
    public function it_can_retrieve()
    {
        $bts  = BalanceTransaction::all();

        $btId = $bts->data[0]->id;
        $bt   = BalanceTransaction::retrieve($btId);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $bt);
        $this->assertEquals($btId, $bt->id);
    }
}
