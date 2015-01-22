<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\Transfer;

interface TransferInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Transfer
     * @link https://stripe.com/docs/api/curl#retrieve_transfer
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Transfer
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Transfers
     * @link https://stripe.com/docs/api/curl#list_transfers
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject
     */
    public static function all($params = [], $options = null);

    /**
     * Create a new transfer
     * @link https://stripe.com/docs/api/curl#create_transfer
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return Transfer
     */
    public static function create($params = [], $options = null);

    /**
     * Cancel a Transfer
     * @link https://stripe.com/docs/api/curl#cancel_transfer
     *
     * @return Transfer
     */
    public function cancel();

    /**
     * Update/Save a Transfer
     * @link https://stripe.com/docs/api/curl#update_transfer
     *
     * @return Transfer
     */
    public function save();
}
