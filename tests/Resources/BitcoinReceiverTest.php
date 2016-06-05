<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     BitcoinReceiverTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BitcoinReceiverTest extends StripeTestCase
{
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
        unset($this->object);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\BitcoinReceiver',
            $this->object
        );
    }

    /** @test */
    public function it_can_get_urls()
    {
        $this->assertSame('/v1/bitcoin/receivers', BitcoinReceiver::classUrl());

        $receiver    = new BitcoinReceiver('abcd/efgh');
        $this->assertSame(
            '/v1/bitcoin/receivers/abcd%2Fefgh',
            $receiver->instanceUrl()
        );
    }

    /** @test */
    public function it_can_list_all()
    {
        $this->createTestBitcoinReceiver();
        $receivers = BitcoinReceiver::all();

        $this->assertTrue(count($receivers->data) > 0);
    }

    /** @test */
    public function it_can_create()
    {
        $receiver = $this->createTestBitcoinReceiver();

        $this->assertSame(100, $receiver->amount);
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
        $receiver = $this->createTestBitcoinReceiver();
        $r        = BitcoinReceiver::retrieve($receiver->id);

        $this->assertSame($receiver->id, $r->id);
    }

    /** @test */
    public function it_can_list_all_transactions()
    {
        $receiver = $this->createTestBitcoinReceiver();
        $transactions = $receiver->transactions->all(['limit' => 1]);

        $this->assertCount(0, $receiver->transactions->data);
        $this->assertCount(1, $transactions->data);
    }

    /** @test */
    public function it_can_refund()
    {
        $receiver = $this->createTestBitcoinReceiver();
        $receiver = BitcoinReceiver::retrieve($receiver->id);

        $this->assertNull($receiver->refund_address);

        $refundAddress = '007, Real refund address, Earth.';
        $receiver->refund([
            'refund_address' => $refundAddress
        ]);

        $this->assertSame($refundAddress, $receiver->refund_address);
    }
}
