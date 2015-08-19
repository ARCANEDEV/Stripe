<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Refund;

/**
 * Interface RefundInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
interface RefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Refund by ID
     * @link https://stripe.com/docs/api/php#retrieve_refund
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * List all refunds
     * @link https://stripe.com/docs/api/php#list_refunds
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Collection|self[]
     */
    public static function all($params = null, $options = null);

    /**
     * Create a Refund
     * @link https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Update/Save a Refund
     * @link https://stripe.com/docs/api/php#update_refund
     *
     * @param  array|string|null $options
     *
     * @return Refund
     */
    public function save($options = null);
}
