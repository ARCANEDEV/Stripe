<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Resource;

class Token extends Resource
{
    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string      $id The ID of the token to retrieve.
     * @param string|null $apiKey
     *
     * @return Token
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
     * @return Token The created token.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }
}
