<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\CouponInterface;
use Arcanedev\Stripe\Resource;

/**
 * Class Coupon
 * @package Arcanedev\Stripe\Resources
 * @link https://stripe.com/docs/api/php#coupons
 *
 * @property string         id
 * @property string         object // "coupon"
 * @property bool           livemode
 * @property int            created
 * @property string         duration
 * @property int            amount_off
 * @property string         currency
 * @property int            duration_in_months
 * @property int            max_redemptions
 * @property AttachedObject metadata
 * @property int            percent_off
 * @property int            redeem_by
 * @property int            times_redeemed
 * @property bool           valid
 */
class Coupon extends Resource implements CouponInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Coupon
     * @link https://stripe.com/docs/api/php#retrieve_coupon
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * List all Coupons
     * @link https://stripe.com/docs/api/php#list_coupons
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|self[]
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Create coupon
     * @link https://stripe.com/docs/api/php#create_coupon
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update/Save a Coupon
     * @link https://stripe.com/docs/api/php#update_coupon
     *
     * @param  array|string|null $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }

    /**
     * Delete a coupon
     * @link https://stripe.com/docs/api/php#delete_coupon
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self
     */
    public function delete($params = [], $options = null)
    {
        return self::scopedDelete($params, $options);
    }
}
