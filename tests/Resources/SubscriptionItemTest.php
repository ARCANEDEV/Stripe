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
        self::retrieveOrCreatePlan(
            $planOneID = 'gold-'.self::generateRandomString(20)
        );
        $customer = self::createTestCustomer();

        $subscription = Subscription::create([
            'plan'     => $planOneID,
            'customer' => $customer->id,
        ]);

        self::retrieveOrCreatePlan(
            $planTwoID = 'gold-'.self::generateRandomString(20)
        );

        // Create
        $subItem = SubscriptionItem::create([
            'plan'         => $planTwoID,
            'subscription' => $subscription->id,
        ]);

        $this->assertSame($planTwoID, $subItem->plan->id);

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

        $this->assertInstanceOf(SubscriptionItem::class, $subItems->data[0]);
        $this->assertSame(2, count($subItems->data));

        // Delete
        $subItem->delete();

        $this->assertTrue($subItem->deleted);
    }
}
