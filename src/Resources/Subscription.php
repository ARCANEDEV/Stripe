<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\SubscriptionInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Subscription
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#subscription_object
 *
 * @property  string                                id
 * @property  string                                object                   // 'subscription'
 * @property  float                                 application_fee_percent
 * @property  boolean                               cancel_at_period_end
 * @property  int                                   canceled_at              // timestamp
 * @property  int                                   current_period_end       // timestamp
 * @property  int                                   current_period_start     // timestamp
 * @property  string                                customer
 * @property  \Arcanedev\Stripe\Resources\Discount  discount
 * @property  int                                   ended_at                 // timestamp
 * @property  \Arcanedev\Stripe\Collection          items
 * @property  \Arcanedev\Stripe\AttachedObject      metadata
 * @property  \Arcanedev\Stripe\Resources\Plan      plan
 * @property  int                                   quantity
 * @property  int                                   start                    // timestamp
 * @property  string                                status
 * @property  float                                 tax_percent
 * @property  int                                   trial_end                // timestamp
 * @property  int                                   trial_start              // timestamp
 */
class Subscription extends StripeResource implements SubscriptionInterface
{
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
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Subscription by id.
     * @link   https://stripe.com/docs/api/php#retrieve_subscription
     *
     * @param  string             $id       The ID of the subscription to retrieve.
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create a Subscription.
     * @link   https://stripe.com/docs/api/php#create_subscription
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

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
    public static function update($id, $params = [], $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Cancel a Subscription.
     * @link   https://stripe.com/docs/api/php#cancel_subscription
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function cancel($params = [], $options = null)
    {
        return self::scopedDelete($params, $options);
    }

    /**
     * Update a Subscription.
     * @link   https://stripe.com/docs/api/php#update_subscription
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }

    /**
     * Delete a Subscription Discount.
     * @link   https://stripe.com/docs/api/php#delete_subscription_discount
     *
     * @return self
     */
    public function deleteDiscount()
    {
        list($response, $opts) = $this->request('delete', $this->instanceUrl() . '/discount');

        $this->refreshFrom(['discount' => null], $opts, true);
        unset($response);

        return $this;
    }
}
