<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Transfer object Interface
 * @link https://stripe.com/docs/api/curl#transfer_object
 *
 * @property string                  id
 * @property string                  object  // "transfer"
 * @property bool                    livemode
 * @property int                     amount
 * @property int                     created
 * @property string                  currency
 * @property int                     date
 * @property string                  status
 * @property string                  type
 * @property string                  balance_transaction
 * @property string                  description
 * @property string                  failure_code
 * @property string                  failure_message
 * @property AttachedObjectInterface metadata
 * @property Object                  bank_account
 * @property CardInterface           card
 * @property string                  recipient
 * @property string                  statement_descriptor
 */
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
     * @param string            $id
     * @param array|string|null $options
     *
     * @return TransferInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Transfers
     * @link https://stripe.com/docs/api/curl#list_transfers
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $options = null);

    /**
     * Create a new transfer
     * @link https://stripe.com/docs/api/curl#create_transfer
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return TransferInterface
     */
    public static function create($params = [], $options = null);

    /**
     * Cancel a Transfer
     * @link https://stripe.com/docs/api/curl#cancel_transfer
     *
     * @return TransferInterface
     */
    public function cancel();

    /**
     * Update/Save a Transfer
     * @link https://stripe.com/docs/api/curl#update_transfer
     *
     * @return TransferInterface
     */
    public function save();
}
