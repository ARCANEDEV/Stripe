<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\Util;

/**
 * @property mixed|null customer
 * @property mixed|null attempted
 * @property mixed|null lines
 */
class Invoice extends Resource
{
    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return Invoice The created invoice.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * @param string      $id The ID of the invoice to retrieve.
     * @param string|null $apiKey
     *
     * @return Invoice
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Invoices.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Invoice The upcoming invoice.
     */
    public static function upcoming($params = null, $apiKey = null)
    {
        $requestor  = new Requestor($apiKey);
        $url        = self::classUrl(get_class()) . '/upcoming';

        list($response, $apiKey) = $requestor->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @return Invoice The saved invoice.
     */
    public function save()
    {
        $class = get_class();
        return self::scopedSave($class);
    }

    /**
     * @return Invoice The paid invoice.
     */
    public function pay()
    {
        $requestor  = new Requestor($this->apiKey);
        $url        = $this->instanceUrl() . '/pay';

        list($response, $apiKey) = $requestor->post($url);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }
}
