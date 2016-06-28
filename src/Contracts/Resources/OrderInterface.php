<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  OrderInterface
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface OrderInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Orders.
     * @link   https://stripe.com/docs/api/php#list_orders
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieves the details of an existing Order.
     * @link   https://stripe.com/docs/api/php#retrieve_order
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a new Order.
     * @link   https://stripe.com/docs/api/php#create_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null);

    /**
     * Update an Order.
     * @link   https://stripe.com/docs/api/php#update_order
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

    /**
     * Update/Save an Order.
     * @link   https://stripe.com/docs/api/php#update_order
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Pay an Order.
     * @link   https://stripe.com/docs/api/php#pay_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function pay($params = [], $options = null);

    /**
     * Return an order.
     * @link   https://stripe.com/docs/api/php#return_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Resources\OrderReturn|array
     */
    public function returnOrder($params = [], $options = null);
}
