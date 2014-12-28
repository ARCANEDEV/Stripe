<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Refund;

use Arcanedev\Stripe\Tests\StripeTestCase;

class RefundTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Refund */
    private $refund;

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
}
