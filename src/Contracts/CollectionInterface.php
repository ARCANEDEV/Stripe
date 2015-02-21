<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Collection;

interface CollectionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List Function
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     *
     * @return Collection|array
     */
    public function all($params = [], $options = null);

    /**
     * Create Function
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|Resource|array
     */
    public function create($params = [], $options = null);

    /**
     * Retrieve Function
     *
     * @param  string            $id
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|Resource|array
     */
    public function retrieve($id, $params = [], $options = null);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if Object is list
     *
     * @return bool
     */
    public function isList();

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get items Count
     *
     * @return int
     */
    public function count();
}
