<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\SkuInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Sku
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#sku_object
 *
 * @property  string                            id
 * @property  string                            object             // 'sku'
 * @property  bool                              active
 * @property  array                             attributes
 * @property  int                               created             // timestamp
 * @property  string                            currency
 * @property  string                            image
 * @property  \Arcanedev\Stripe\StripeObject    inventory
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  array                             package_dimensions
 * @property  int                               price
 * @property  string                            product
 * @property  int                               updated             // timestamp
 */
class Sku extends StripeResource implements SkuInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all SKUs.
     * @link   https://stripe.com/docs/api/php#list_skus
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a SKU.
     * @link   https://stripe.com/docs/api/php#retrieve_sku
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
     * Create a SKU.
     * @link   https://stripe.com/docs/api/php#create_sku
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update a SKU.
     * @link   https://stripe.com/docs/api/php#update_sku
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Update/Save a SKU.
     * @link   https://stripe.com/docs/api/php#update_sku
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
     * Delete a SKU.
     * @link   https://stripe.com/docs/api/php#delete_sku
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null)
    {
        return $this->scopedDelete($params, $options);
    }
}
