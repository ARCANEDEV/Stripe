<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class ChargeTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class ChargeTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS         = 'Arcanedev\\Stripe\\Resources\\Charge';
    const BITCOIN_RECEIVER_CLASS = 'Arcanedev\\Stripe\\Resources\\BitcoinReceiver';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Charge */
    private $charge;

    /** @var array */
    private $chargeData = [];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->charge     = new Charge;
        $this->chargeData = [
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => static::getValidCardData(),
        ];
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->charge);
        $this->chargeData = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->charge);
    }

    /** @test */
    public function it_can_get_url()
    {
        $this->assertEquals(Charge::classUrl(), '/v1/charges');

        $this->charge = new Charge('abcd/efgh');

        $this->assertEquals($this->charge->instanceUrl(), '/v1/charges/abcd%2Fefgh');
    }

    /** @test */
    public function it_can_list_all()
    {
        $charges = Charge::all();

        $this->assertTrue($charges->isList());
        $this->assertEquals('/v1/charges', $charges->url);
    }

    /** @test */
    public function it_can_create()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->charge);
        $this->assertTrue($this->charge->paid);
        $this->assertFalse($this->charge->refunded);
    }

    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\CardException
     * @expectedExceptionCode 402
     */
    public function it_must_throw_card_error_on_declined_card()
    {
        $declinedCard = [
            'number'    => '4000000000000002',
            'exp_month' => '3',
            'exp_year'  => '2020'
        ];

        Charge::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => $declinedCard
        ]);
    }

    /** @test */
    public function it_can_create_with_idempotent_key()
    {
        $this->charge = Charge::create($this->chargeData, [
            'idempotency_key' => self::generateRandomString(),
        ]);

        $this->assertTrue($this->charge->paid);
        $this->assertFalse($this->charge->refunded);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $charge       = Charge::create($this->chargeData);
        $this->charge = Charge::retrieve($charge->id);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->charge);
        $this->assertEquals($charge->id, $this->charge->id);
    }

    /** @test */
    public function it_can_refund_with_total_amount()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->refund();
        $this->assertTrue($this->charge->refunded);
        $this->assertEquals(1, count($this->charge->refunds->data));

        $refund = $this->charge->refunds->data[0];
        $this->assertEquals('refund', $refund->object);
        $this->assertEquals(100, $refund->amount);
    }

    /** @test */
    public function it_can_refund_with_partial_amounts()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->refund(['amount' => 50,]);
        $this->assertFalse($this->charge->refunded);
        $this->assertEquals(1, count($this->charge->refunds->data));

        $this->charge->refund(['amount' => 50,]);
        $this->assertTrue($this->charge->refunded);
        $this->assertEquals(2, count($this->charge->refunds->data));
    }

    /** @test */
    public function it_can_capture()
    {
        $this->charge = Charge::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => self::getValidCardData(),
            'capture'   => false
        ]);

        $this->assertFalse($this->charge->captured);
        $this->charge->capture();
        $this->assertTrue($this->charge->captured);
    }

    /** @test */
    public function it_can_create_with_bitcoin_receiver_source()
    {
        $receiver = $this->createTestBitcoinReceiver('do+fill_now@stripe.com');

        $charge = Charge::create([
            'amount'   => 100,
            'currency' => 'usd',
            'source'   => $receiver->id
        ]);

        $this->assertEquals($receiver->id, $charge->source->id);
        $this->assertEquals('bitcoin_receiver', $charge->source->object);
        $this->assertEquals('succeeded', $charge->status);
        $this->assertEquals(self::BITCOIN_RECEIVER_CLASS, get_class($charge->source));
    }

    /** @test */
    public function testCanUpdateDispute()
    {
        // TODO: Complete testCanUpdateDispute() implementation
    }

    /** @test */
    public function testCanCloseDispute()
    {
        // TODO: Complete testCanCloseDispute() implementation
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Metadata Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_update_one_metadata()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->metadata['test'] = 'foo bar';
        $this->charge->save();

        $charge = Charge::retrieve($this->charge->id);
        $this->assertEquals('foo bar', $charge->metadata['test']);
    }

    /** @test */
    public function it_can_update_all_metadata()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->metadata = ['test' => 'foo bar'];
        $this->charge->save();

        $charge = Charge::retrieve($this->charge->id);
        $this->assertEquals('foo bar', $charge->metadata['test']);
    }

    /** @test */
    public function it_can_mark_as_fraudulent()
    {
        $charge = Charge::create($this->chargeData);

        $charge->refunds->create();
        $charge->markAsFraudulent();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertEquals('fraudulent', $updatedCharge['fraud_details']['user_report']);
    }

    /** @test */
    public function it_can_mark_as_safe()
    {
        $charge = Charge::create($this->chargeData);

        $charge->markAsSafe();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertEquals('safe', $updatedCharge['fraud_details']['user_report']);
    }
}
