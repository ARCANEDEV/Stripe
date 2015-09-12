<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\StripeObject;
use Arcanedev\Stripe\StripeResource;

/**
 * Interface  CollectionInterface
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CollectionInterface
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
     * @return StripeObject|StripeResource|array
     */
    public function create($params = [], $options = null);

    /**
     * Retrieve Function.
     *
     * @param  string             $id
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return StripeObject|StripeResource|array
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
