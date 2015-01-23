<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\ListObject;

interface ListObjectInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List Function
     *
     * @param  array $params
     *
     * @throws ApiException
     *
     * @return ListObject
     */
    public function all($params = []);

    /**
     * Create Function
     *
     * @param  array $params
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|Resource
     */
    public function create($params = []);

    /**
     * Retrieve Function
     *
     * @param  string $id
     * @param  array  $params
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|Resource
     */
    public function retrieve($id, $params = []);

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
