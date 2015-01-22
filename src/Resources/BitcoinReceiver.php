<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\BitcoinReceiverInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resource;

/**
 * @property string         id
 * @property string         object
 * @property string         description
 * @property string         currency
 * @property int            amount
 * @property AttachedObject metadata
 * @property ListObject     transactions
 */
class BitcoinReceiver extends Resource implements BitcoinReceiverInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The class URL for this resource.
     * It needs to be special cased because it doesn't fit into the standard resource pattern.
     *
     * @param  string $class Ignored.
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return "/v1/bitcoin/receivers";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve Bitcoin Receiver
     *
     * @param  string      $id
     * @param  string|null $apiKey
     *
     * @return BitcoinReceiver
     */
    public static function retrieve($id, $apiKey = null)
    {
        return parent::scopedRetrieve($id, $apiKey);
    }

    /**
     * List all Bitcoin Receivers
     *
     * @param  array|null  $params
     * @param  string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        return parent::scopedAll($params, $apiKey);
    }

    /**
     * Create Bitcoin Receiver Object
     *
     * @param  array|null  $params
     * @param  string|null $apiKey
     *
     * @return BitcoinReceiver
     */
    public static function create($params = [], $apiKey = null)
    {
        return parent::scopedCreate($params, $apiKey);
    }

    /**
     * Save Bitcoin Receiver Object
     *
     * @return BitcoinReceiver
     */
    public function save()
    {
        return parent::scopedSave();
    }
}
