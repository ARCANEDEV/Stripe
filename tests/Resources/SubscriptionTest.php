<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Subscription;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     SubscriptionTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SubscriptionTest extends StripeTestCase
{
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
    public function it_must_throw_invalid_request_exception_on_instance_url()
    {
        (new Subscription)->instanceUrl();
    }

    /**
     * @test
     */
    public function it_can_create_update_cancel()
    {
        $plan     = self::retrieveOrCreatePlan();
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
    public function it_can_delete_discount()
    {
        $plan     = self::retrieveOrCreatePlan();
        $couponID = '25off-' . self::generateRandomString(20);
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
