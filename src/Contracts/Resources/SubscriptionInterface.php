<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Subscription;

interface SubscriptionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Cancel a Subscription
     * @link https://stripe.com/docs/api/php#cancel_subscription
     *
     * @param  array|null $params
     *
     * @return Subscription
     */
    public function cancel($params = []);

    /**
     * Update/Save a Subscription
     * @link https://stripe.com/docs/api/php#update_subscription
     *
     * @return Subscription
     */
    public function save();

    /**
     * Delete a Subscription Discount
     * @link https://stripe.com/docs/api/curl#delete_subscription_discount
     *
     * @return Subscription
     */
    public function deleteDiscount();
}
