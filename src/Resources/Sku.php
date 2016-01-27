<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\SkuInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Sku
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api#skus
 *
 * @property  string                            id
 * @property  string                            object             // 'sku'
 * @property  bool                              livemode
 * @property  bool                              active
 * @property  int                               created
 * @property  int                               updated
 * @property  array                             attributes
 * @property  string                            currency
 * @property  array                             inventory
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  int                               price
 * @property  string                            product
 * @property  string                            image
 * @property  array                             package_dimensions
 */
class Sku extends StripeResource implements SkuInterface
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
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

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
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update a Sku.
     *
     * @link   https://stripe.com/docs/api#update_sku
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
     * List all SKUs.
     *
     * @link   https://stripe.com/docs/api#list_skus
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
     * Delete a product SKU.
     *
     * @link   https://stripe.com/docs/api#delete_sku
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
