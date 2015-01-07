<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Refund;

use Arcanedev\Stripe\Tests\StripeTestCase;

class RefundTest extends StripeTestCase
{
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
    /**
     * @test
     */
    public function testCanGetInstanceUrl()
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
    public function testMustThrowInvalidRequestExceptionWhenIdEmpty()
    {
        $this->refund->instanceUrl();
    }

    /**
     * @test
     */
    public function testCanGetAll()
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

    /**
     * @test
     */
    public function testCanCreate()
    {
        $charge = self::createTestCharge();

        /** @var Refund $ref */
        $ref    = $charge->refunds->create(['amount' => 100]);
        $this->assertEquals(100, $ref->amount);
        $this->assertEquals($charge->id, $ref->charge);
    }

    /**
     * @test
     */
    public function testCanUpdateAndRetrieve()
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

    /**
     * @test
     */
    public function testCreateForBitcoin()
    {
        $receiver = $this->createTestBitcoinReceiver("do+fill_now@stripe.com");

        $charge   = Charge::create([
            "amount" => $receiver->amount,
            "currency" => $receiver->currency,
            "description" => $receiver->description,
            'source' => $receiver->id
        ]);

        $ref = $charge->refunds->create([
            'amount' => $receiver->amount,
            'refund_address' => 'ABCDEF'
        ]);

        $this->assertEquals($receiver->amount, $ref->amount);
        $this->assertNotNull($ref->id);
    }
}
