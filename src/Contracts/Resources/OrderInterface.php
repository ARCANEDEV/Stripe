<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;

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
     * Retrieves the details of an existing order.
     *
     * @link   https://stripe.com/docs/api#retrieve_order
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Creates a new order object.
     *
     * @link   https://stripe.com/docs/api#create_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Update an order.
     *
     * @link   https://stripe.com/docs/api#update_order
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * List all orders.
     *
     * @link   https://stripe.com/docs/api#list_orders
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = null, $options = null);

    /**
     * Pay an order.
     *
     * @link   https://stripe.com/docs/api#pay_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function pay($params = null, $options = null);
}
