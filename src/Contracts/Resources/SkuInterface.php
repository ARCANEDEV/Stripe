<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  SkuInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SkuInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a SKU.
     * @link   https://stripe.com/docs/api/php#retrieve_sku
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a SKU.
     * @link   https://stripe.com/docs/api/php#create_sku
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Update a SKU.
     * @link   https://stripe.com/docs/api/php#update_sku
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * List all SKUs.
     * @link   https://stripe.com/docs/api/php#list_skus
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null);

    /**
     * Delete a SKU.
     * @link   https://stripe.com/docs/api/php#delete_sku
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null);
}
