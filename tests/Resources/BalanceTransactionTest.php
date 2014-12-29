<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BalanceTransaction;

use Arcanedev\Stripe\Tests\StripeTestCase;

class BalanceTransactionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\BalanceTransaction';

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
    /**
     * @test
     */
    public function testCanGetAll()
    {
        $balanceTransactions  = BalanceTransaction::all();

        $this->assertTrue($balanceTransactions->isList());
        $this->assertEquals($balanceTransactions->url, '/v1/balance/history');
    }

    public function testCanRetrieve()
    {
        $bts  = BalanceTransaction::all();

        $btId = $bts->data[0]->id;
        $bt   = BalanceTransaction::retrieve($btId);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $bt);
        $this->assertEquals($btId, $bt->id);
    }
}
