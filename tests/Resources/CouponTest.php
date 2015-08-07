<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class CouponTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class CouponTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\Coupon';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Coupon */
    private $coupon;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->coupon = new Coupon;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->coupon);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->coupon);
    }

    /** @test */
    public function it_can_list_all()
    {
        $coupons = Coupon::all();

        $this->assertTrue($coupons->isList());
        $this->assertEquals('/v1/coupons', $coupons->url);
    }

    /** @test */
    public function it_can_create_and_save()
    {
        $couponId   = $this->getCouponId();
        $coupon     = Coupon::create([
            'id'                    => $couponId,
            'percent_off'           => 25,
            'duration'              => 'repeating',
            'duration_in_months'    => 5,
        ]);

        $this->assertEquals($couponId, $coupon->id);
        $this->assertEquals(25, $coupon->percent_off);

        $coupon->metadata['foo'] = 'bar';
        $coupon->save();

        $stripeCoupon = Coupon::retrieve($couponId);
        $this->assertEquals($coupon->metadata, $stripeCoupon->metadata);
    }

    /** @test */
    public function it_can_delete()
    {
        $couponId     = $this->getCouponId();
        $this->coupon = Coupon::create([
            'id'                    => $couponId,
            'percent_off'           => 25,
            'duration'              => 'repeating',
            'duration_in_months'    => 5,
        ]);
        $customer = self::createTestCustomer([
            'coupon' => $couponId
        ]);

        $this->assertTrue(isset($customer->discount));
        $this->assertTrue(isset($customer->discount->coupon));
        $this->assertEquals($couponId, $customer->discount->coupon->id);

        $customer->deleteDiscount();
        $this->assertFalse(isset($customer->discount));

        $this->coupon->delete();
        $this->assertTrue($this->coupon->deleted);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return string
     */
    private function getCouponId()
    {
        return 'test_coupon-' . self::generateRandomString(20);
    }
}
