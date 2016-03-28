<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     DiscountTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DiscountTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_delete()
    {
        $couponId = 'test-coupon-' . self::generateRandomString(20);

        Coupon::create([
            'id'                 => $couponId,
            'percent_off'        => 25,
            'duration'           => 'repeating',
            'duration_in_months' => 5,
        ]);

        $customer = self::createTestCustomer([
            'coupon' => $couponId
        ]);

        $this->assertNotNull($customer->discount);
        $this->assertInstanceOf('Arcanedev\Stripe\Resources\Discount', $customer->discount);
        $this->assertNotNull($customer->discount->coupon);
        $this->assertInstanceOf('Arcanedev\Stripe\Resources\Coupon', $customer->discount->coupon);
        $this->assertEquals($couponId, $customer->discount->coupon->id);

        $customer->deleteDiscount();

        $this->assertNull($customer->discount);

        $customer = Customer::retrieve($customer->id);

        $this->assertNull($customer->discount);
    }
}
