<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\TransferInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * Transfer object
 * @link https://stripe.com/docs/api/curl#transfer_object
 *
 * @property string         id
 * @property string         object  // "transfer"
 * @property bool           livemode
 * @property int            amount
 * @property int            created
 * @property string         currency
 * @property int            date
 * @property string         status
 * @property string         type
 * @property string         balance_transaction
 * @property string         description
 * @property string         failure_code
 * @property string         failure_message
 * @property AttachedObject metadata
 * @property Object         bank_account
 * @property Card           card
 * @property string         recipient
 * @property string     statement_descriptor
 */
class Transfer extends Resource implements TransferInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Transfer
     * @link https://stripe.com/docs/api/curl#retrieve_transfer
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Transfer
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve($id, $apiKey);
    }

    /**
     * List all Transfers
     * @link https://stripe.com/docs/api/curl#list_transfers
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        return self::scopedAll($params, $apiKey);
    }

    /**
     * Create a new transfer
     * @link https://stripe.com/docs/api/curl#create_transfer
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Transfer
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate($params, $apiKey);
    }

    /**
     * Cancel a Transfer
     * @link https://stripe.com/docs/api/curl#cancel_transfer
     *
     * @return Transfer
     */
    public function cancel()
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/cancel');

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Update/Save a Transfer
     * @link https://stripe.com/docs/api/curl#update_transfer
     *
     * @return Transfer
     */
    public function save()
    {
        return self::scopedSave();
    }
}
