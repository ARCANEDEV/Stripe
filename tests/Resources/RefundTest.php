<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Refund;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     RefundTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RefundTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Stripe\Resources\Refund */
    private $refund;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->refund = new Refund;
    }

    public function tearDown()
    {
        unset($this->refund);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Refund::class, $this->refund);
    }

    /** @test */
    public function it_can_get_instance_url()
    {
        $this->refund->id = $refundId = 'refund_random_id';

        $this->assertSame("/v1/refunds/$refundId", $this->refund->instanceUrl());
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
    public function it_can_get_all()
    {
        $refunds = Refund::all();

        // Fetches all refunds on this test account.
        $this->assertTrue($refunds['has_more']);
        $this->assertSame(10, count($refunds->data));
    }

    /** @test */
    public function it_can_list_all_for_charge()
    {
        $charge  = self::createTestCharge();
        $refundA = Refund::create(['charge' => $charge->id, 'amount' => 100]);
        $refundB = Refund::create(['charge' => $charge->id, 'amount' => 50]);
        $all     = Refund::all(['charge' => $charge]);

        $this->assertFalse($all['has_more']);
        $this->assertCount(2, $all->data);
        $this->assertSame($all->data[0]->id,     $refundB->id);
        $this->assertSame($all->data[0]->amount, $refundB->amount);
        $this->assertSame($all->data[1]->id,     $refundA->id);
        $this->assertSame($all->data[1]->amount, $refundA->amount);
    }

    /** @test */
    public function it_can_create()
    {
        $charge       = self::createTestCharge();
        $this->refund = Refund::create(['charge' => $charge->id, 'amount' => 100]);

        $this->assertSame(100, $this->refund->amount);
        $this->assertSame($charge->id, $this->refund->charge);
    }

    /** @test */
    public function it_can_update_and_retrieve()
    {
        $charge       = self::createTestCharge();
        $this->refund = Refund::create(['charge' => $charge->id, 'amount' => 100]);
        $this->refund = Refund::update($this->refund->id, [
            'metadata' => ['key' => 'value'],
        ]);

        $this->refund = Refund::retrieve($this->refund->id);

        $this->assertSame('value', $this->refund->metadata['key']);
    }

    /** @test */
    public function it_can_save_and_retrieve()
    {
        $charge       = self::createTestCharge();
        $this->refund = Refund::create(['charge' => $charge->id, 'amount' => 100]);

        $this->refund->metadata['key'] = 'value';
        $this->refund->save();

        $this->refund = Refund::retrieve($this->refund->id);

        $this->assertSame('value', $this->refund->metadata['key']);
    }

    // Deprecated charge endpoints:
    //================================================================>

    /** @test */
    public function it_can_create_via_charge()
    {
        $charge       = self::createTestCharge();
        $this->refund = $charge->refunds->create(['amount' => 100]);

        $this->assertSame(100, $this->refund->amount);
        $this->assertSame($charge->id, $this->refund->charge);
    }

    /** @test */
    public function it_can_update_and_retrieve_via_charge()
    {
        $charge       = self::createTestCharge();
        $this->refund = $charge->refunds->create(['amount' => 100]);
        $this->refund->metadata['key'] = 'value';
        $this->refund->save();

        $this->refund = $charge->refunds->retrieve($this->refund->id);

        $this->assertSame('value', $this->refund->metadata['key'], 'value');
    }

    /** @test */
    public function it_can_get_all_via_charge()
    {
        $charge  = self::createTestCharge();
        $refundA = $charge->refunds->create(['amount' => 50]);
        $refundB = $charge->refunds->create(['amount' => 50]);
        $all     = $charge->refunds->all();

        $this->assertFalse($all['has_more']);
        $this->assertCount(2, $all->data);
        $this->assertSame($all->data[0]->id,     $refundB->id);
        $this->assertSame($all->data[0]->amount, $refundB->amount);
        $this->assertSame($all->data[1]->id,     $refundA->id);
        $this->assertSame($all->data[1]->amount, $refundA->amount);
    }
}
