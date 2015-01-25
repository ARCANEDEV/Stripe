<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Refund;
use Arcanedev\Stripe\Tests\StripeTestCase;

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
        $refundId = 'refund_random_id';
        $chargeId = 'refund_random_id';

        $this->refund->id     = $refundId;
        $this->refund->charge = $chargeId;

        $this->assertEquals(
            "/v1/charges/$chargeId/refunds/$refundId",
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
    public function it_can_list_all()
    {
        $charge = self::createTestCharge();
        $refA = $charge->refunds->create([
            'amount' => 50
        ]);
        $refB = $charge->refunds->create([
            'amount' => 50
        ]);

        $all = $charge->refunds->all();
        $this->assertEquals(false, $all['has_more']);
        $this->assertEquals(2, count($all->data));
        $this->assertEquals($refB->id, $all->data[0]->id);
        $this->assertEquals($refA->id, $all->data[1]->id);
    }

    /** @test */
    public function it_can_create()
    {
        $charge = self::createTestCharge();

        /** @var Refund $ref */
        $ref    = $charge->refunds->create(['amount' => 100]);
        $this->assertEquals(100, $ref->amount);
        $this->assertEquals($charge->id, $ref->charge);
    }

    /** @test */
    public function it_can_update_and_retrieve()
    {
        $charge = self::createTestCharge();
        /** @var Refund $ref */
        $ref    = $charge->refunds->create([
            'amount' => 100
        ]);

        $ref->metadata["key"] = "value";
        $ref->save();

        $ref = $charge->refunds->retrieve($ref->id);
        $this->assertEquals("value", $ref->metadata["key"], "value");
    }

    /** @test */
    public function it_can_create_refund_for_bitcoin()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $charge   = Charge::create([
            "amount"      => $receiver->amount,
            "currency"    => $receiver->currency,
            "description" => $receiver->description,
            'source'      => $receiver->id
        ]);

        $refund   = $charge->refunds->create([
            'amount'         => $receiver->amount,
            'refund_address' => 'ABCDEF'
        ]);

        $this->assertEquals($receiver->amount, $refund->amount);
        $this->assertNotNull($refund->id);
    }
}
