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
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * These constants are possible representations of the status field.
     *
     * @link https://stripe.com/docs/api#subscription_object-status
     */
    const STATUS_ACTIVE   = 'active';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_TRIALING = 'trialing';
    const STATUS_UNPAID   = 'unpaid';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Subscriptions.
     * @link   https://stripe.com/docs/api/php#list_subscriptions
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Subscription by id.
     * @link   https://stripe.com/docs/api/php#retrieve_subscription
     *
     * @param  string             $id       The ID of the subscription to retrieve.
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Subscription.
     * @link   https://stripe.com/docs/api/php#create_subscription
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null);

    /**
     * Updated a subscription.
     * @link   https://stripe.com/docs/api/php#update_subscription
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

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
