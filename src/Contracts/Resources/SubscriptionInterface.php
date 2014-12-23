<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;

/**
 * Subscription Object Interface
 * @link https://stripe.com/docs/api/php#subscription_object
 *
 * @property string                  id
 * @property string                  object // "subscription"
 * @property boolean                 cancel_at_period_end
 * @property string                  customer
 * @property PlanInterface           plan
 * @property int                     quantity
 * @property int                     start
 * @property string                  status
 * @property float                   application_fee_percent
 * @property int                     canceled_at
 * @property int                     current_period_start
 * @property int                     current_period_end
 * @property Object                  discount
 * @property int                     ended_at
 * @property AttachedObjectInterface metadata
 * @property int                     trial_end
 * @property int                     trial_start
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
     * @param array $params
     *
     * @return SubscriptionInterface
     */
    public function cancel($params = []);

    /**
     * Update/Save a Subscription
     * @link https://stripe.com/docs/api/php#update_subscription
     *
     * @return SubscriptionInterface
     */
    public function save();

    /**
     * Delete a Subscription Discount
     * @link https://stripe.com/docs/api/curl#delete_subscription_discount
     *
     * @return SubscriptionInterface
     */
    public function deleteDiscount();
}
