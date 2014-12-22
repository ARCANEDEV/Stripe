<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTest;

class CouponTest extends StripeTest
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
        $couponId   = $this->getCouponId();
        Coupon::create([
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

        $customer = Customer::retrieve($customer->id);
        $this->assertFalse(isset($customer->discount));
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
