<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Tests\StripeTestCase;

class BitcoinReceiverTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var BitcoinReceiver */
    protected $object;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\BitcoinReceiver';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->object = new BitcoinReceiver;
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
        $classUrl    = BitcoinReceiver::classUrl();
        $this->assertEquals($classUrl, '/v1/bitcoin/receivers');

        $receiver    = new BitcoinReceiver('abcd/efgh');
        $instanceUrl = $receiver->instanceUrl();
        $this->assertEquals($instanceUrl, '/v1/bitcoin/receivers/abcd%2Fefgh');
    }

    /**
     * @test
     */
    public function testCanCreate()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $this->assertEquals(100, $receiver->amount);
        $this->assertNotNull($receiver->id);
    }

    /**
     * @test
     */
    public function testCanSave()
    {
        // TODO: Check if save method is supported - [POST] /v1/bitcoin/receivers/btcrcv_{id}
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $r = BitcoinReceiver::retrieve($receiver->id);
        $this->assertEquals($receiver->id, $r->id);
    }

    /**
     * @test
     */
    public function testCanList()
    {
        $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $receivers = BitcoinReceiver::all();
        $this->assertTrue(count($receivers->data) > 0);
    }

    /**
     * @test
     */
    public function testListTransactions()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");
        $this->assertEquals(0, count($receiver->transactions->data));

        $transactions = $receiver->transactions->all(["limit" => 1]);
        $this->assertEquals(1, count($transactions->data));
    }
}
