<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\BalanceTransactionInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     BalanceTransaction
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#balance_transaction_object
 *
 * @property  string                        id
 * @property  string                        object        // "balance_transaction"
 * @property  int                           amount
 * @property  int                           available_on  // timestamp
 * @property  int                           created       // timestamp
 * @property  string                        currency
 * @property  string                        description
 * @property  int                           fee
 * @property  \Arcanedev\Stripe\Collection  fee_details
 * @property  int                           net
 * @property  string                        source
 * @property  \Arcanedev\Stripe\Collection  sourced_transfers
 * @property  string                        status
 * @property  string                        type
 */
class BalanceTransaction extends StripeResource implements BalanceTransactionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The class URL for this resource. It needs to be special cased because
     * it doesn't fit into the standard resource pattern.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return '/v1/balance/history';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieving a Balance Transaction.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_balance_transaction
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * List balance history.
     *
     * @link   https://stripe.com/docs/api/php#balance_history
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
