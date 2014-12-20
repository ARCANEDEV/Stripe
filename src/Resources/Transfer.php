<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * @property string     id
 * @property mixed|null status
 * @property mixed|null metadata
 */
class Transfer extends Resource
{
    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string      $id The ID of the transfer to retrieve.
     * @param string|null $apiKey
     *
     * @return Transfer
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Transfers.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return Transfer The created transfer.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * @return Transfer The canceled transfer.
     */
    public function cancel()
    {
        $requestor  = new Requestor($this->apiKey);
        $url        = $this->instanceUrl() . '/cancel';

        list($response, $apiKey) = $requestor->post($url);
        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * @return Transfer The saved transfer.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }
}
