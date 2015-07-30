<?php namespace Arcanedev\Stripe\Tests\Dummies;

use Arcanedev\Stripe\StripeResource;

class ResourceObject extends StripeResource
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
     * @return \Arcanedev\Stripe\Collection
     */
    public static function all($params = [], $apiKey = null)
    {
        return parent::scopedAll($params, $apiKey);
    }
}
