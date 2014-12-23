<?php namespace Arcanedev\Stripe\Contracts\Resources;
use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Coupon Object Interface
 * @link https://stripe.com/docs/api/php#coupons
 *
 * @property string                  id
 * @property string                  object // "coupon"
 * @property bool                    livemode
 * @property int                     created
 * @property string                  duration
 * @property int                     amount_off
 * @property string                  currency
 * @property int                     duration_in_months
 * @property int                     max_redemptions
 * @property AttachedObjectInterface metadata
 * @property int                     percent_off
 * @property int                     redeem_by
 * @property int                     times_redeemed
 * @property bool                    valid
 */
interface CouponInterface
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
     * @return CouponInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * List all Coupons
     * @link https://stripe.com/docs/api/php#list_coupons
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $apiKey = null);

    /**
     * Create coupon
     * @link https://stripe.com/docs/api/php#create_coupon
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return CouponInterface
     */
    public static function create($params = [], $apiKey = null);

    /**
     * Update/Save a Coupon
     * @link https://stripe.com/docs/api/php#update_coupon
     *
     * @return CouponInterface
     */
    public function save();

    /**
     * Delete a coupon
     * @link https://stripe.com/docs/api/php#delete_coupon
     *
     * @param array $params
     *
     * @return CouponInterface
     */
    public function delete($params = []);
}
