<?php namespace Arcanedev\Stripe\Contracts\Resources;
use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Balance Transaction Object Interface
 *
 * @link https://stripe.com/docs/api/php#balance_transaction_object
 *
 * @property string              id
 * @property string              object// "balance_transaction"
 * @property int                 amount
 * @property int                 available_on
 * @property int                 created
 * @property string              currency
 * @property int                 fee
 * @property ListObjectInterface fee_details
 * @property int                 net
 * @property string              status
 * @property string              type
 * @property string              description
 * @property string              source
 */
interface BalanceTransactionInterface
{
    /**
     * @param string      $id     The ID of the balance transaction to retrieve.
     * @param string|null $apiKey
     *
     * @return BalanceTransactionInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * Get an array of BalanceTransactions.
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array
     */
    public static function all($params = null, $apiKey = null);
}
