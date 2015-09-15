<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\ProductInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Product
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api#products
 *
 * @property  string          object
 * @property  bool            livemode
 * @property  bool            active
 * @property  int             created
 * @property  int             updated
 * @property  array           images
 * @property  AttachedObject  metadata
 * @property  string          name
 * @property  bool            shippable
 * @property  Collection      skus
 * @property  array           attributes
 * @property  string          caption
 * @property  string          description
 * @property  string          url
 */
class Product extends StripeResource implements ProductInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a product.
     *
     * @link   https://stripe.com/docs/api#retrieve_product
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
     * Create a product.
     *
     * @link   https://stripe.com/docs/api#create_product
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
     * Update a product.
     *
     * @link   https://stripe.com/docs/api#update_product
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
     * List all products.
     *
     * @link   https://stripe.com/docs/api#list_products
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
