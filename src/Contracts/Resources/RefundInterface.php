<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  RefundInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Refunds.
     * @link   https://stripe.com/docs/api/php#list_refunds
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Refund by ID.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_refund
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Refund.
     * @link   https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null);

    /**
     * Update a Refund.
     * @link   https://stripe.com/docs/api/php#update_refund
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

    /**
     * Update/Save a Refund.
     * @link   https://stripe.com/docs/api/php#update_refund
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);
}
