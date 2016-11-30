<?php namespace Arcanedev\Stripe\Contracts;

/**
 * Interface  Collection
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List Function.
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public function all($params = [], $options = null);

    /**
     * Create Function.
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\StripeObject|\Arcanedev\Stripe\StripeResource|array
     */
    public function create($params = [], $options = null);

    /**
     * Retrieve Function.
     *
     * @param  string             $id
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\StripeObject|\Arcanedev\Stripe\StripeResource|array
     */
    public function retrieve($id, $params = [], $options = null);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if Object is list.
     *
     * @return bool
     */
    public function isList();

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get items Count.
     *
     * @return int
     */
    public function count();
}
