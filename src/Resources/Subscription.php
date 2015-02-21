<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\SubscriptionInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * Subscription Object
 * @link https://stripe.com/docs/api/php#subscription_object
 *
 * @property string         id
 * @property string         object // "subscription"
 * @property boolean        cancel_at_period_end
 * @property string         customer
 * @property Plan           plan
 * @property int            quantity
 * @property int            start
 * @property string         status
 * @property float          application_fee_percent
 * @property int            canceled_at
 * @property int            current_period_start
 * @property int            current_period_end
 * @property Object         discount
 * @property int            ended_at
 * @property AttachedObject metadata
 * @property int            trial_end
 * @property int            trial_start
 */
class Subscription extends Resource implements SubscriptionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @throws InvalidRequestException
     *
     * @return string The API URL for this Stripe subscription.
     */
    public function instanceUrl()
    {
        // TODO: Refactor this method
        $id         = $this['id'];
        $customerId = $this['customer'];

        if (! $id) {
            throw new InvalidRequestException(
                'Could not determine which URL to request: class instance has invalid ID: '. $id,
                null
            );
        }

        $base           = parent::classUrl('Arcanedev\\Stripe\\Resources\\Customer');
        $customerId     = urlencode(str_utf8($customerId));
        $subscriptionId = urlencode(str_utf8($id));

        return "$base/$customerId/subscriptions/$subscriptionId";
    }

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
    public function cancel($params = [], $options = null)
    {
        return parent::scopedDelete($params, $options);
    }

    /**
     * Update/Save a Subscription
     * @link https://stripe.com/docs/api/php#update_subscription
     *
     * @param  array|string|null $options
     *
     * @return Subscription
     */
    public function save($options = null)
    {
        return parent::scopedSave($options);
    }

    /**
     * Delete a Subscription Discount
     * @link https://stripe.com/docs/api/curl#delete_subscription_discount
     *
     * @return Subscription
     */
    public function deleteDiscount()
    {
        $url = $this->instanceUrl() . '/discount';
        list($response, $opts) = $this->request('delete', $url);
        unset($response);
        $this->refreshFrom(['discount' => null], $opts, true);

        return $this;
    }
}
