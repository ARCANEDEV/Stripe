<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface CouponInterface
{
    /**
     * @param string      $id The ID of the coupon to retrieve.
     * @param string|null $apiKey
     *
     * @return CouponInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Coupons.
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return CouponInterface The created coupon.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @return CouponInterface The saved coupon.
     */
    public function save();

    /**
     * @param array|null $params
     *
     * @return CouponInterface The deleted coupon.
     */
    public function delete($params = null);
}
