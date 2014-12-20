<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

use Arcanedev\Stripe\Exceptions\InvalidRequestErrorException;

/**
 * @property string     id
 * @property mixed|null status
 * @property mixed|null plan
 * @property mixed|null discount
 * @property mixed|null cancel_at_period_end
 * @property mixed|null quantity
 */
class Subscription extends Resource
{
    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @throws InvalidRequestErrorException
     *
     * @return string The API URL for this Stripe subscription.
     */
    public function instanceUrl()
    {
        $id       = $this['id'];
        $customer = $this['customer'];

        if ( ! $id ) {
            throw new InvalidRequestErrorException(
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

    /**
     * @param array|null $params
     * @return Subscription The deleted subscription.
     */
    public function cancel($params=null)
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
        $requestor = new Requestor($this->apiKey);
        $url       = $this->instanceUrl() . '/discount';

        list($response, $apiKey) = $requestor->delete($url);

        $this->refreshFrom(['discount' => null], $apiKey, true);
    }
}
