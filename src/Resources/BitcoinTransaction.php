<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resource;

use Arcanedev\Stripe\Contracts\Resources\BitcoinTransactionInterface;

class BitcoinTransaction extends Resource implements BitcoinTransactionInterface
{
    /**
     * The class URL for this resource.
     * It needs to be special cased because it doesn't fit into the standard resource pattern.
     *
     * @param string $class Ignored.
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return "/v1/bitcoin/transactions";
    }

    /**
     * Retrieve a Bitcoin Transaction
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return BitcoinTransaction
     */
    public static function retrieve($id, $apiKey = null)
    {
        return parent::scopedRetrieve($id, $apiKey);
    }

    /**
     * List all Bitcoin Transactions
     *
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = null, $apiKey = null)
    {

        return self::scopedAll($params, $apiKey);
    }
}
