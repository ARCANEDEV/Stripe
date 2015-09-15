<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;

interface SkuInterface
{
    /* ------------------------------------------------------------------------------------------------
         |  Main Functions
         | ------------------------------------------------------------------------------------------------
         */
    /**
     * Retrieve a Sku.
     *
     * @link   https://stripe.com/docs/api#retrieve_sku
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Sku.
     *
     * @link   https://stripe.com/docs/api#create_sku
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Update a Sku.
     *
     * @link   https://stripe.com/docs/api#update_sku
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * List all SKUs.
     *
     * @link   https://stripe.com/docs/api#list_skus
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = null, $options = null);
}
