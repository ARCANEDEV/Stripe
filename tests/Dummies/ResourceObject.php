<?php namespace Arcanedev\Stripe\Tests\Dummies;

use Arcanedev\Stripe\Resource;

class ResourceObject extends Resource
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get All
     *
     * @param array $params
     * @param null  $apiKey
     *
     * @return \Arcanedev\Stripe\ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        return parent::scopedAll($params, $apiKey);
    }
}
