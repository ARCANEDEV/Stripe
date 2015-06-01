<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\TransferInterface;
use Arcanedev\Stripe\Resource;

/**
 * Class Transfer
 * @package Arcanedev\Stripe\Resources
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
 * @property string         statement_descriptor
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
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * List all Transfers
     * @link https://stripe.com/docs/api/curl#list_transfers
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|self[]
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Create a new transfer
     * @link https://stripe.com/docs/api/curl#create_transfer
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Transfer|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Cancel a Transfer
     * @link https://stripe.com/docs/api/curl#cancel_transfer
     *
     * @return self
     */
    public function cancel()
    {
        list($response, $opts) = $this->request('post', $this->instanceUrl() . '/cancel');
        $this->refreshFrom($response, $opts);

        return $this;
    }

    /**
     * Created transfer reversal
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return TransferReversal
     */
    public function reverse($params = null, $options = null)
    {
        list($response, $opts) = $this->request(
            'post', $this->instanceUrl() . '/reversals', $params, $options
        );
        $this->refreshFrom($response, $opts);

        return $this;
    }

    /**
     * Update/Save a Transfer
     * @link https://stripe.com/docs/api/curl#update_transfer
     *
     * @param  array|string|null $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }
}
