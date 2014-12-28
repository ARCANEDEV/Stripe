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
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    public function testCanGetInstanceUrl()
    {
        (new Subscription)->instanceUrl();
    }

    /**
     * @test
     */
    public function testCanCreateUpdateCancel()
    {
        $plan = self::retrieveOrCreatePlan();

        $customer = self::createTestCustomer();

        /** @var Subscription $subscription */
        $subscription = $customer->subscriptions->create([
            'plan' => $plan->id
        ]);

        $this->assertEquals($subscription->status, 'active');
        $this->assertEquals($subscription->plan->id, $plan->id);

        $subscription->quantity = 2;
        $subscription->save();

        $subscription = $customer->subscriptions->retrieve($subscription->id);
        $this->assertEquals($subscription->status, 'active');
        $this->assertEquals($subscription->plan->id, $plan->id);
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
        $plan = self::retrieveOrCreatePlan();

        $couponID = '25off-' . self::randomString();
        self::retrieveOrCreateCoupon($couponID);

        $customer = self::createTestCustomer();

        /** @var Subscription $subscription */
        $subscription = $customer->subscriptions->create([
            'plan'      => $plan->id,
            'coupon'    => $couponID
        ]);

        $this->assertEquals($subscription->status, 'active');
        $this->assertEquals($subscription->plan->id, $plan->id);
        $this->assertEquals($subscription->discount->coupon->id, $couponID);

        $subscription->deleteDiscount();
        $subscription = $customer->subscriptions->retrieve($subscription->id);
        $this->assertNull($subscription->discount);
    }
}
