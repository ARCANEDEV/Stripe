<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\SubscriptionInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
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
     * Get the instance URL.
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl()
    {
        if (is_null($id = $this['id'])) {
            throw new InvalidRequestException(
                "Could not determine which URL to request: class instance has invalid ID [null]",
                null
            );
        }

        $base           = self::classUrl('Arcanedev\\Stripe\\Resources\\Customer');
        $customerId     = urlencode(str_utf8($this['customer']));
        $subscriptionId = urlencode(str_utf8($id));

        return "$base/$customerId/subscriptions/$subscriptionId";
    }

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
