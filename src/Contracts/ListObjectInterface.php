<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\Exceptions\ApiException;

interface ListObjectInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string     $id
     * @param array|null $params
     *
     * @throws ApiException
     *
     * @return array|ObjectInterface
     */
    public function retrieve($id, $params = null);

    /**
     * @param array|null $params
     *
     * @throws ApiException
     *
     * @return array|ObjectInterface
     */
    public function all($params = null);

    /**
     * @param array|null $params
     *
     * @throws ApiException
     *
     * @return array|Object
     */
    public function create($params = null);
}
