<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Subscription;
use Arcanedev\Stripe\Resources\SubscriptionItem;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     SubscriptionItemTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SubscriptionItemTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_create_update_retrieve_list_delete()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer();

        $subscription = Subscription::create([
            'plan'     => $plan->id,
            'customer' => $customer->id,
        ]);

        // Create
        $subItem = SubscriptionItem::create([
            'plan'         => $plan->id,
            'subscription' => $subscription->id,
        ]);

        $this->assertSame($subItem->plan->id, $plan->id);

        // Save
        $subItem->quantity = 2;
        $subItem->save();

        // Retrieve
        $subItem = SubscriptionItem::retrieve(['id' => $subItem->id]);

        $this->assertSame($subItem->quantity, 2);

        // Update
        $subItem = SubscriptionItem::update($subItem->id, ['quantity' => 3]);

        $this->assertSame($subItem->quantity, 3);

        // List
        $subItems = SubscriptionItem::all([
            'subscription' => $subscription->id,
            'limit'        => 3,
        ]);

        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\SubscriptionItem', $subItems->data[0]);
        $this->assertSame(2, count($subItems->data));

        // Delete
        $subItem->delete();

        $this->assertTrue($subItem->deleted);
    }
}
