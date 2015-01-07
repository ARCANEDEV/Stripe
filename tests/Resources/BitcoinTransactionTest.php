<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Resources\BitcoinTransaction;
use Arcanedev\Stripe\Tests\StripeTestCase;

class BitcoinTransactionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var BitcoinTransaction */
    protected $object;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\BitcoinTransaction';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->object = new BitcoinTransaction;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->object);
    }

    /**
     * @test
     */
    public function testCanGetUrls()
    {
        $classUrl = BitcoinTransaction::classUrl();
        $this->assertEquals($classUrl, '/v1/bitcoin/transactions');

        $instanceUrl = (new BitcoinTransaction('abcd/efgh'))
            ->instanceUrl();
        $this->assertEquals($instanceUrl, '/v1/bitcoin/transactions/abcd%2Fefgh');
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $receiverResponse = BitcoinReceiver::retrieve($receiver->id);
        $transactions     = $receiverResponse->transactions;
        $this->assertEquals(1, count($transactions["data"]));

        $r = BitcoinTransaction::retrieve($transactions->data[0]->id);
        $this->assertNotNull($r->id);
    }

    /**
     * @test
     */
    public function testCanList()
    {
        $receiver     = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $transactions = BitcoinTransaction::all();
        $this->assertEquals($transactions->url, '/v1/bitcoin/transactions');
        $this->assertTrue(count($transactions->data) > 0);

        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\BitcoinTransaction',
            $transactions->data[0]
        );
    }
}
