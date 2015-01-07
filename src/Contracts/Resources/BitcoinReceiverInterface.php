<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\ListObjectInterface;

interface BitcoinReceiverInterface
{
    /**
     * The class URL for this resource.
     * It needs to be special cased because it doesn't fit into the standard resource pattern.
     *
     * @param string $class Ignored.
     *
     * @return string
     */
    public static function classUrl($class = '');

    /**
     * Retrieve Bitcoin Receiver
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return BitcoinReceiverInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * List all Bitcoin Receivers
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = null, $apiKey = null);

    /**
     * Create Bitcoin Receiver Object
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return BitcoinReceiverInterface
     */
    public static function create($params = null, $apiKey = null);

    /**
     * Save Bitcoin Receiver Object
     *
     * @return BitcoinReceiverInterface
     */
    public function save();
}
