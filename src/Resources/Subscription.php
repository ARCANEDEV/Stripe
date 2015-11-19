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
 * @property  string                            id
 * @property  string                            object                   // "subscription"
 * @property  boolean                           cancel_at_period_end
 * @property  string                            customer
 * @property  Plan                              plan
 * @property  int                               quantity
 * @property  int                               start
 * @property  string                            status
 * @property  float                             application_fee_percent
 * @property  int                               canceled_at
 * @property  int                               current_period_start
 * @property  int                               current_period_end
 * @property  Object                            discount
 * @property  int                               ended_at
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  int                               trial_end
 * @property  int                               trial_start
 *
 * @todo:     Complete the properties.
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
     * @throws InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl()
    {
        $id         = $this['id'];

        if ( ! $id) {
            throw new InvalidRequestException(
                'Could not determine which URL to request: class instance has invalid ID: '. $id,
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
     *
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
     * Update/Save a Subscription.
     *
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
     *
     * @link   https://stripe.com/docs/api/curl#delete_subscription_discount
     *
     * @return self
     */
    public function deleteDiscount()
    {
        $url = $this->instanceUrl() . '/discount';
        list($response, $opts) = $this->request('delete', $url);

        $this->refreshFrom(['discount' => null], $opts, true);
        unset($response);

        return $this;
    }
}
