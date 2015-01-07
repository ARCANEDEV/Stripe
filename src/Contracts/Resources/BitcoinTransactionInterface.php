<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\ListObjectInterface;

interface BitcoinTransactionInterface
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
     * Retrieve a Bitcoin Transaction
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return BitcoinTransactionInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * List all Bitcoin Transactions
     *
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = null, $apiKey = null);
}