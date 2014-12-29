<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;

use Arcanedev\Stripe\Tests\StripeTestCase;

class CouponTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Coupon */
    private $coupon;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\Coupon';

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
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->coupon);
    }

    /**
     * @test
     */
    public function testCanGetAll()
    {
        $coupons = Coupon::all();

        $this->assertTrue($coupons->isList());
        $this->assertEquals('/v1/coupons', $coupons->url);
    }

    /**
     * @test
     */
    public function testCanCreateAndSave()
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

    /**
     * @test
     */
    public function testCanDelete()
    {
        $couponId    = $this->getCouponId();
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
        return 'test_coupon-' . self::randomString();
    }
}
