<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Resource;

class Event extends Resource
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $id The ID of the event to retrieve.
     * @param string|null $apiKey
     *
     * @return Event
     */
    public static function retrieve($id, $apiKey=null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * An array of Stripe Events.
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }
}
