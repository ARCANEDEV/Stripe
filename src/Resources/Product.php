<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ProductInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Product
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#product_object
 *
 * @property  string                            id
 * @property  string                            object       // 'product'
 * @property  bool                              active
 * @property  array                             attributes
 * @property  string                            caption
 * @property  int                               created      // timestamp
 * @property  array                             deactivate_on
 * @property  string                            description
 * @property  array                             images
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            name
 * @property  array|null                        package_dimensions
 * @property  bool                              shippable
 * @property  \Arcanedev\Stripe\Collection      skus
 * @property  int                               updated
 * @property  string                            url
 */
class Product extends StripeResource implements ProductInterface
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
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create a Product.
     * @link   https://stripe.com/docs/api/php#create_product
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update a Product.
     * @link   https://stripe.com/docs/api/php#update_product
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }

    /**
     * List all Products.
     * @link   https://stripe.com/docs/api/php#list_products
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Delete a Product.
     * @link   https://stripe.com/docs/api/php#delete_product
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null)
    {
        return $this->scopedDelete($params, $options);
    }
}
