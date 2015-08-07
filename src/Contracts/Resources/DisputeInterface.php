<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Dispute;

/**
 * Interface DisputeInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
interface DisputeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get a dispute by id
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Dispute
     */
    public static function retrieve($id, $options = null);

    /**
     * Get all disputes
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|Dispute
     */
    public static function all($params = null, $options = null);

    /**
     * Save dispute
     *
     * @param  array|string|null $options
     *
     * @return Dispute
     */
    public function save($options = null);

    /**
     * Close dispute
     *
     * @param  array|string|null $options
     *
     * @return Dispute
     */
    public function close($options = null);
}