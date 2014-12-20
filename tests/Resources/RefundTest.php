<?php namespace Arcanedev\Stripe\Tests\Resources;


use Arcanedev\Stripe\Resources\Refund;
use Arcanedev\Stripe\Tests\StripeTest;

class RefundTest extends StripeTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function testCreate()
    {
        $charge = self::createTestCharge();

        /** @var Refund $ref */
        $ref    = $charge->refunds->create(['amount' => 100]);
        $this->assertEquals(100, $ref->amount);
        $this->assertEquals($charge->id, $ref->charge);
    }

    public function testUpdateAndRetrieve()
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

    public function testList()
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
}
