<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

class ApplicationFee extends Resource
{
    /**
     * This is a special case because the application fee endpoint has an
     *    underscore in it. The parent `className` function strips underscores.
     *
     * @param string $class
     *
     * @return string The name of the class.
     */
    public static function className($class)
    {
        return 'application_fee';
    }

    /**
     * @param string      $id The ID of the application fee to retrieve.
     * @param string|null $apiKey
     *
     * @return ApplicationFee
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * @param string|null $params
     * @param string|null $apiKey
     *
     * @return array An array of application fees.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * @param string|null $params
     *
     * @return ApplicationFee The refunded application fee.
     */
    public function refund($params = null)
    {
        $requestor = new Requestor($this->apiKey);

        $url = $this->instanceUrl() . '/refund';

        list($response, $apiKey) = $requestor->post($url, $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }
}
