<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  TransferInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TransferInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Transfer.
     * @link   https://stripe.com/docs/api/php#retrieve_transfer
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Transfers.
     * @link   https://stripe.com/docs/api/php#list_transfers
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create a transfer.
     * @link   https://stripe.com/docs/api/php#create_transfer
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Cancel a Transfer.
     * @link   https://stripe.com/docs/api/php#cancel_transfer
     *
     * @return self
     */
    public function cancel();

    /**
     * Created transfer reversal.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Contracts\Resources\TransferReversalInterface
     */
    public function reverse($params = null, $options = null);

    /**
     * Update a Transfer.
     * @link   https://stripe.com/docs/api/php#update_transfer
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);
}
