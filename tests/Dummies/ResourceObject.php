<?php namespace Arcanedev\Stripe\Tests\Dummies;

use Arcanedev\Stripe\StripeResource;

/**
 * Class ResourceObject
 * @package Arcanedev\Stripe\Tests\Dummies
 */
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
