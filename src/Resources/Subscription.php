<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\SubscriptionInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * @property string     id
 * @property mixed|null status
 * @property mixed|null plan
 * @property mixed|null discount
 * @property mixed|null cancel_at_period_end
 * @property mixed|null quantity
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
        $id       = $this['id'];
        $customer = $this['customer'];

        if ( ! $id ) {
            throw new InvalidRequestException(
                "Could not determine which URL to request: class instance has invalid ID: $id", null
            );
        }

        $id       = Requestor::utf8($id);
        $customer = Requestor::utf8($customer);

        $base         = self::classUrl('Arcanedev\\Stripe\\Resources\\Customer');
        $customerExtn = urlencode($customer);
        $extn         = urlencode($id);

        return "$base/$customerExtn/subscriptions/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param array|null $params
     *
     * @return Subscription The deleted subscription.
     */
    public function cancel($params = null)
    {
        $class = get_class();

        return self::scopedDelete($class, $params);
    }

    /**
     * @return Subscription The saved subscription.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * @return Subscription The updated subscription.
     */
    public function deleteDiscount()
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->delete($this->instanceUrl() . '/discount');

        $this->refreshFrom(['discount' => null], $apiKey, true);
    }
}
