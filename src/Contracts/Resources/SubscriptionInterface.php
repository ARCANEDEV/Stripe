<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Subscription;

/**
 * Interface SubscriptionInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
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
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Subscription
     */
    public function cancel($params = [], $options = null);

    /**
     * Update/Save a Subscription
     * @link https://stripe.com/docs/api/php#update_subscription
     *
     * @param  array|string|null $options
     *
     * @return Subscription
     */
    public function save($options = null);

    /**
     * Delete a Subscription Discount
     * @link https://stripe.com/docs/api/curl#delete_subscription_discount
     *
     * @return Subscription
     */
    public function deleteDiscount();
}
