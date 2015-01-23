<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\BitcoinReceiver;

interface BitcoinReceiverInterface
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
    public static function classUrl($class = '');

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
    public static function retrieve($id, $apiKey = null);

    /**
     * List all Bitcoin Receivers
     *
     * @param  array|null  $params
     * @param  string|null $apiKey
     *
     * @return ListObject|array
     */
    public static function all($params = [], $apiKey = null);

    /**
     * Create Bitcoin Receiver Object
     *
     * @param  array|null  $params
     * @param  string|null $apiKey
     *
     * @return BitcoinReceiver|array
     */
    public static function create($params = [], $apiKey = null);

    /**
     * Save Bitcoin Receiver Object
     *
     * @return BitcoinReceiver
     */
    public function save();
}
