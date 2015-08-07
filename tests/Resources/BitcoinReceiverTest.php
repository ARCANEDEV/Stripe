<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class BitcoinReceiverTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class BitcoinReceiverTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\BitcoinReceiver';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var BitcoinReceiver */
    protected $object;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->object = new BitcoinReceiver;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->object);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->object);
    }

    /** @test */
    public function it_can_get_urls()
    {
        $this->assertEquals('/v1/bitcoin/receivers', BitcoinReceiver::classUrl());

        $receiver    = new BitcoinReceiver('abcd/efgh');
        $this->assertEquals(
            '/v1/bitcoin/receivers/abcd%2Fefgh',
            $receiver->instanceUrl()
        );
    }

    /** @test */
    public function it_can_list_all()
    {
        $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $receivers = BitcoinReceiver::all();
        $this->assertTrue(count($receivers->data) > 0);
    }

    /** @test */
    public function it_can_create()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $this->assertEquals(100, $receiver->amount);
        $this->assertNotNull($receiver->id);
    }

    /** @test */
    public function it_can_save()
    {
        // TODO: Check if save method is supported - [POST] /v1/bitcoin/receivers/btcrcv_{id}
    }

    /** @test */
    public function it_can_retrieve()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $r = BitcoinReceiver::retrieve($receiver->id);
        $this->assertEquals($receiver->id, $r->id);
    }

    /** @test */
    public function it_can_list_all_transactions()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");
        $this->assertEquals(0, count($receiver->transactions->data));

        $transactions = $receiver->transactions->all(['limit' => 1]);
        $this->assertEquals(1, count($transactions->data));
    }
}
