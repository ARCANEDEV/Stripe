<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Refund;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class RefundTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class RefundTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const REFUND_CLASS = 'Arcanedev\\Stripe\\Resources\\Refund';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Refund */
    private $refund;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->refund = new Refund;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->refund);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::REFUND_CLASS, $this->refund);
    }

    /** @test */
    public function it_can_get_instance_url()
    {
        $this->refund->id = $refundId = 'refund_random_id';

        $this->assertEquals(
            "/v1/refunds/$refundId",
            $this->refund->instanceUrl()
        );
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    public function it_must_throw_invalid_request_exception_when_id_is_empty()
    {
        $this->refund->instanceUrl();
    }

    /** @test */
    public function it_can_create()
    {
        $charge = self::createTestCharge();

        $this->refund = Refund::create([
            'amount' => 100,
            'charge' => $charge->id
        ]);

        $this->assertEquals(100, $this->refund->amount);
        $this->assertEquals($charge->id, $this->refund->charge);
    }

    /** @test */
    public function it_can_update_and_retrieve()
    {
        $charge       = self::createTestCharge();
        $this->refund = Refund::create([
            'amount' => 100,
            'charge' => $charge->id
        ]);

        $this->refund->metadata['key'] = 'value';
        $this->refund->save();

        $this->refund = Refund::retrieve($this->refund->id);
        $this->assertSame('value', $this->refund->metadata['key'], 'value');
    }

    /** @test */
    public function it_can_list_all_for_charge()
    {
        $charge = self::createTestCharge();
        $refA   = Refund::create([
            'amount' => 100,
            'charge' => $charge->id
        ]);
        $refB   = Refund::create([
            'amount' => 50,
            'charge' => $charge->id
        ]);
        $all    = Refund::all([
            'charge' => $charge
        ]);

        $this->assertFalse($all['has_more']);
        $this->assertEquals(2, count($all->data));
        $this->assertEquals($refB->id, $all->data[0]->id);
        $this->assertEquals($refA->id, $all->data[1]->id);
    }

    /** @test */
    public function it_can_list_all()
    {
        $all = Refund::all();

        // Fetches all refunds on this test account.
        $this->assertTrue($all['has_more']);
        $this->assertEquals(10, count($all->data));
    }

    /** @test */
    public function it_can_create_refund_for_bitcoin()
    {
        $receiver = $this->createTestBitcoinReceiver('do+fill_now@stripe.com');
        $charge   = Charge::create([
            'amount'      => $receiver->amount,
            'currency'    => $receiver->currency,
            'description' => $receiver->description,
            'source'      => $receiver->id
        ]);

        $this->refund   = Refund::create([
            'amount'         => $receiver->amount,
            'refund_address' => 'ABCDEF',
            'charge'         => $charge->id
        ]);

        $this->assertEquals($receiver->amount, $this->refund->amount);
        $this->assertNotNull($this->refund->id);
    }

    // Deprecated charge endpoints:
    //================================================================>

    /** @test */
    public function it_can_create_via_charge()
    {
        $charge       = self::createTestCharge();
        $this->refund = $charge->refunds->create([
            'amount' => 100
        ]);

        $this->assertEquals(100, $this->refund->amount);
        $this->assertEquals($charge->id, $this->refund->charge);
    }

    /** @test */
    public function it_can_update_and_retrieve_via_charge()
    {
        $charge       = self::createTestCharge();
        $this->refund = $charge->refunds->create([
            'amount' => 100
        ]);
        $this->refund->metadata['key'] = 'value';
        $this->refund->save();

        $this->refund = $charge->refunds->retrieve($this->refund->id);

        $this->assertEquals('value', $this->refund->metadata['key'], 'value');
    }

    /** @test */
    public function it_can_list_all_via_charge()
    {
        $charge = self::createTestCharge();
        $refA   = $charge->refunds->create([
            'amount' => 50
        ]);
        $refB   = $charge->refunds->create([
            'amount' => 50
        ]);
        $all    = $charge->refunds->all();

        $this->assertFalse($all['has_more']);
        $this->assertEquals(2, count($all->data));
        $this->assertEquals($refB->id, $all->data[0]->id);
        $this->assertEquals($refA->id, $all->data[1]->id);
    }

    /** @test */
    public function it_can_create_for_bitcoin_via_charge()
    {
        $receiver = $this->createTestBitcoinReceiver('do+fill_now@stripe.com');
        $charge   = Charge::create([
            'amount'      => $receiver->amount,
            'currency'    => $receiver->currency,
            'description' => $receiver->description,
            'source'      => $receiver->id
        ]);

        $ref      = $charge->refunds->create([
            'amount'         => $receiver->amount,
            'refund_address' => 'ABCDEF'
        ]);

        $this->assertEquals($receiver->amount, $ref->amount);
        $this->assertNotNull($ref->id);
    }
}
