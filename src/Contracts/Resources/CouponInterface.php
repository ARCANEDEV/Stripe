<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  CouponInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CouponInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Coupons.
     * @link   https://stripe.com/docs/api/php#list_coupons
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Coupon.
     * @link   https://stripe.com/docs/api/php#retrieve_coupon
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Coupon.
     * @link   https://stripe.com/docs/api/php#create_coupon
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update a Coupon.
     * @link   https://stripe.com/docs/api/php#update_coupon
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

    /**
     * Update/Save a Coupon.
     * @link   https://stripe.com/docs/api/php#update_coupon
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Delete a Coupon.
     * @link   https://stripe.com/docs/api/php#delete_coupon
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null);
}
