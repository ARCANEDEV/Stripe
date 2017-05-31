<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ChargeTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ChargeTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Stripe\Resources\Charge */
    private $charge;

    /** @var array */
    private $chargeData = [];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->charge     = new Charge;
        $this->chargeData = [
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => 'tok_visa',
        ];
    }

    public function tearDown()
    {
        unset($this->charge);
        $this->chargeData = [];

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Charge::class, $this->charge);
    }

    /** @test */
    public function it_can_get_url()
    {
        $this->assertSame(Charge::classUrl(), '/v1/charges');

        $this->charge = new Charge('abcd/efgh');

        $this->assertSame($this->charge->instanceUrl(), '/v1/charges/abcd%2Fefgh');
    }

    /** @test */
    public function it_can_list_all()
    {
        $charges = Charge::all();

        $this->assertTrue($charges->isList());
        $this->assertSame('/v1/charges', $charges->url);
    }

    /** @test */
    public function it_can_create()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->assertInstanceOf(Charge::class, $this->charge);
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
        Charge::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => [
                // Declined card
                'number'    => '4000000000000002',
                'exp_month' => '3',
                'exp_year'  => '2020',
            ],
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
        $this->assertSame(200, $this->charge->getLastResponse()->getStatusCode());
    }

    /** @test */
    public function it_can_retrieve()
    {
        $charge       = Charge::create($this->chargeData);
        $this->charge = Charge::retrieve($charge->id);

        $this->assertInstanceOf(Charge::class, $this->charge);
        $this->assertSame($charge->id, $this->charge->id);
        $this->assertSame(200, $this->charge->getLastResponse()->getCode());
    }

    /** @test */
    public function it_can_refund_with_total_amount()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->refund();
        $this->assertTrue($this->charge->refunded);
        $this->assertSame(1, count($this->charge->refunds->data));

        $refund = $this->charge->refunds->data[0];
        $this->assertSame('refund', $refund->object);
        $this->assertSame(100, $refund->amount);
    }

    /** @test */
    public function it_can_refund_with_partial_amounts()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->refund(['amount' => 50,]);
        $this->assertFalse($this->charge->refunded);
        $this->assertSame(1, count($this->charge->refunds->data));

        $this->charge->refund(['amount' => 50,]);
        $this->assertTrue($this->charge->refunded);
        $this->assertSame(2, count($this->charge->refunds->data));
    }

    /** @test */
    public function it_can_capture()
    {
        $this->charge = Charge::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => 'tok_visa',
            'capture'   => false,
        ]);

        $this->assertFalse($this->charge->captured);
        $this->charge->capture();
        $this->assertTrue($this->charge->captured);
    }

    /** @test */
    public function it_can_create_with_bitcoin_receiver_source()
    {
        $receiver = $this->createTestBitcoinReceiver();

        $charge = Charge::create([
            'amount'   => 100,
            'currency' => 'usd',
            'source'   => $receiver->id,
        ]);

        $this->assertSame($receiver->id, $charge->source->id);
        $this->assertSame('bitcoin_receiver', $charge->source->object);
        $this->assertSame('succeeded', $charge->status);
        $this->assertSame(BitcoinReceiver::class, get_class($charge->source));
    }

    /** @test */
    public function it_can_update()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->assertNull($this->charge->metadata['test']);

        Charge::update($this->charge->id, [
            'metadata' => [
                'test' => 'foo bar',
            ],
        ]);

        $this->charge = Charge::retrieve($this->charge->id);
        $this->assertSame('foo bar', $this->charge->metadata['test']);
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

    /* -----------------------------------------------------------------
     |  Metadata Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_update_one_metadata()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->metadata['test'] = 'foo bar';
        $this->charge->save();

        $charge = Charge::retrieve($this->charge->id);
        $this->assertSame('foo bar', $charge->metadata['test']);
    }

    /** @test */
    public function it_can_update_all_metadata()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->metadata = ['test' => 'foo bar'];
        $this->charge->save();

        $charge = Charge::retrieve($this->charge->id);

        $this->assertSame('foo bar', $charge->metadata['test']);
        $this->assertSame(200, $charge->getLastResponse()->getStatusCode());
    }

    /** @test */
    public function it_can_mark_as_fraudulent()
    {
        $charge = Charge::create($this->chargeData);

        $charge->refunds->create();
        $charge->markAsFraudulent();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertSame('fraudulent', $updatedCharge['fraud_details']['user_report']);
    }

    /** @test */
    public function it_can_mark_as_safe()
    {
        $charge = Charge::create($this->chargeData);

        $charge->markAsSafe();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertSame('safe', $updatedCharge['fraud_details']['user_report']);
    }
}
