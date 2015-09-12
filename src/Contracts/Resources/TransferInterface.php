<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;

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
     *
     * @link   https://stripe.com/docs/api/curl#retrieve_transfer
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Transfers.
     *
     * @link   https://stripe.com/docs/api/curl#list_transfers
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create a new transfer.
     *
     * @link   https://stripe.com/docs/api/curl#create_transfer
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Cancel a Transfer.
     *
     * @link   https://stripe.com/docs/api/curl#cancel_transfer
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
     * @return TransferReversalInterface
     */
    public function reverse($params = null, $options = null);

    /**
     * Update/Save a Transfer.
     *
     * @link   https://stripe.com/docs/api/curl#update_transfer
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);
}
