<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Subscription;

use Arcanedev\Stripe\Tests\StripeTestCase;

class SubscriptionTest extends StripeTestCase
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
    public function testCanCreateUpdateCancel()
    {
        $planID = 'gold-' . self::randomString();
        self::retrieveOrCreatePlan($planID);

        $customer = self::createTestCustomer();

        /** @var Subscription $subscription */
        $subscription = $customer->subscriptions->create([
            'plan' => $planID
        ]);

        $this->assertEquals($subscription->status, 'active');
        $this->assertEquals($subscription->plan->id, $planID);

        $subscription->quantity = 2;
        $subscription->save();

        $subscription = $customer->subscriptions->retrieve($subscription->id);
        $this->assertEquals($subscription->status, 'active');
        $this->assertEquals($subscription->plan->id, $planID);
        $this->assertEquals($subscription->quantity, 2);

        $subscription->cancel(['at_period_end' => true]);

        $subscription = $customer->subscriptions->retrieve($subscription->id);
        $this->assertEquals($subscription->status, 'active');
        $this->assertTrue($subscription->cancel_at_period_end);
    }

    /**
     * @test
     */
    public function testCanDeleteDiscount()
    {
        $planID = 'gold-' . self::randomString();
        self::retrieveOrCreatePlan($planID);

        $couponID = '25off-' . self::randomString();
        self::retrieveOrCreateCoupon($couponID);

        $customer = self::createTestCustomer();

        /** @var Subscription $subscription */
        $subscription = $customer->subscriptions->create([
            'plan'      => $planID,
            'coupon'    => $couponID
        ]);

        $this->assertEquals($subscription->status, 'active');
        $this->assertEquals($subscription->plan->id, $planID);
        $this->assertEquals($subscription->discount->coupon->id, $couponID);

        $subscription->deleteDiscount();
        $subscription = $customer->subscriptions->retrieve($subscription->id);
        $this->assertNull($subscription->discount);
    }
}
