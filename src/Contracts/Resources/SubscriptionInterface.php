<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  SubscriptionInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SubscriptionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Cancel a Subscription.
     * @link   https://stripe.com/docs/api/php#cancel_subscription
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function cancel($params = [], $options = null);

    /**
     * Update a Subscription.
     * @link   https://stripe.com/docs/api/php#update_subscription
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Delete a Subscription Discount.
     * @link   https://stripe.com/docs/api/php#delete_subscription_discount
     *
     * @return self
     */
    public function deleteDiscount();
}
