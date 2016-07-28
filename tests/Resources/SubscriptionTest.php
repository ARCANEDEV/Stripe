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
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionMessage Could not determine which URL to request: Arcanedev\Stripe\Resources\Subscription instance has invalid ID:
     */
    public function it_must_throw_invalid_request_exception_on_instance_url()
    {
        (new Subscription)->instanceUrl();
    }

    /** @test */
    public function it_can_create_update_list_cancel_via_customer()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer();

        /** @var \Arcanedev\Stripe\Resources\Subscription $subscription */
        $subscription = $customer->subscriptions->create(['plan' => $plan->id]);

        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertSame($plan->id, $subscription->plan->id);

        $subscription->quantity = 2;
        $subscription->save();
        $subscription = $customer->subscriptions->retrieve($subscription->id);

        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertSame($plan->id, $subscription->plan->id);
        $this->assertSame(2,         $subscription->quantity);

        $subscriptions = $customer->subscriptions->all(['limit' => 3]);
        $this->assertInstanceOf(
            'Arcanedev\Stripe\Resources\Subscription',
            $subscriptions->data[0]
        );

        $subscription->cancel(['at_period_end' => true]);
        $subscription = $customer->subscriptions->retrieve($subscription->id);

        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertTrue($subscription->cancel_at_period_end);
    }

    /** @test */
    public function it_can_create_update_list_cancel()
    {
        $plan         = self::retrieveOrCreatePlan();
        $customer     = self::createTestCustomer();
        $subscription = Subscription::create(['plan' => $plan->id, 'customer' => $customer]);

        $this->assertSame(Subscription::STATUS_ACTIVE,  $subscription->status);
        $this->assertSame($plan->id, $subscription->plan->id);

        $subscription->quantity = 2;
        $subscription->save();
        $subscription = Subscription::retrieve($subscription->id);

        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertSame($plan->id,                   $subscription->plan->id);
        $this->assertSame(2,                           $subscription->quantity);

        $subscription = Subscription::update($subscription->id, [
            'quantity' => 3,
        ]);

        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertSame($plan->id,                   $subscription->plan->id);
        $this->assertSame(3,                           $subscription->quantity);

        $subscriptions = Subscription::all(['customer' => $customer, 'plan' => $plan->id, 'limit' => 3]);

        $this->assertInstanceOf(
            'Arcanedev\Stripe\Resources\Subscription',
            $subscriptions->data[0]
        );

        $subscription->cancel(['at_period_end' => true]);

        $subscription = Subscription::retrieve($subscription->id);

        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertTrue($subscription->cancel_at_period_end);
    }

    /** @test */
    public function it_can_delete_discount()
    {
        $customer = self::createTestCustomer();
        $plan     = self::retrieveOrCreatePlan();
        self::retrieveOrCreateCoupon(
            $couponID = '25off-' . self::generateRandomString(20)
        );

        /** @var Subscription $subscription */
        $subscription = $customer->subscriptions->create([
            'plan'   => $plan->id,
            'coupon' => $couponID,
        ]);

        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertSame($plan->id, $subscription->plan->id);
        $this->assertSame($couponID, $subscription->discount->coupon->id);

        $subscription->deleteDiscount();
        $subscription = $customer->subscriptions->retrieve($subscription->id);
        $this->assertNull($subscription->discount);
    }
}
