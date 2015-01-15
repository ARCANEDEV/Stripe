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
     * Retrieving a Balance Transaction
     *
     * @param string            $id
     * @param array|string|null $options
     *
     * @return BalanceTransactionInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * List balance history
     *
     * @param array|null  $params
     * @param array|string|null $options
     *
     * @return ListObjectInterface
     */
    public static function all($params = null, $options = null);
}
