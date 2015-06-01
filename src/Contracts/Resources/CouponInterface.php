<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Coupon;

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
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Coupon
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Coupons
     * @link https://stripe.com/docs/api/php#list_coupons
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|Coupon[]
     */
    public static function all($params = [], $options = null);

    /**
     * Create coupon
     * @link https://stripe.com/docs/api/php#create_coupon
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Coupon|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save a Coupon
     * @link https://stripe.com/docs/api/php#update_coupon
     *
     * @param  array|string|null $options
     *
     * @return Coupon
     */
    public function save($options = null);

    /**
     * Delete a coupon
     * @link https://stripe.com/docs/api/php#delete_coupon
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Coupon
     */
    public function delete($params = [], $options);
}
