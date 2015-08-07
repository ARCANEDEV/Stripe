<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class DiscountTest
 * @package Arcanedev\Stripe\Tests\Resources
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
        $id = 'test-coupon-' . self::generateRandomString(20);

        Coupon::create([
            'id'                 => $id,
            'percent_off'        => 25,
            'duration'           => 'repeating',
            'duration_in_months' => 5,
        ]);

        $customer = self::createTestCustomer([
            'coupon' => $id
        ]);

        $this->assertNotNull($customer->discount);
        $this->assertNotNull($customer->discount->coupon);
        $this->assertEquals($id, $customer->discount->coupon->id);

        $customer->deleteDiscount();

        $this->assertNull($customer->discount);

        $customer = Customer::retrieve($customer->id);

        $this->assertNull($customer->discount);
    }
}
