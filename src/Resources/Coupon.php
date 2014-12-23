<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\CouponInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resource;

/**
 * Coupon Object
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
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Coupon
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * List all Coupons
     * @link https://stripe.com/docs/api/php#list_coupons
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * Create coupon
     * @link https://stripe.com/docs/api/php#create_coupon
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Coupon
     */
    public static function create($params = [], $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * Update/Save a Coupon
     * @link https://stripe.com/docs/api/php#update_coupon
     *
     * @return Coupon
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * Delete a coupon
     * @link https://stripe.com/docs/api/php#delete_coupon
     *
     * @param array $params
     *
     * @return Coupon
     */
    public function delete($params = [])
    {
        $class = get_class();

        return self::scopedDelete($class, $params);
    }
}
