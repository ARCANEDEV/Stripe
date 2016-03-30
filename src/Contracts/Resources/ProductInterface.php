<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  ProductInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ProductInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a product.
     * @link   https://stripe.com/docs/api/php#retrieve_product
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Product.
     * @link   https://stripe.com/docs/api/php#create_product
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Update a Product.
     * @link   https://stripe.com/docs/api/php#update_product
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * List all Products.
     * @link   https://stripe.com/docs/api/php#list_products
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null);

    /**
     * Delete a Product.
     * @link   https://stripe.com/docs/api/php#delete_product
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null);
}
