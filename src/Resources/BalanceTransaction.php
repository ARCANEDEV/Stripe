<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\BalanceTransactionInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resource;

/**
 * Balance Transaction Object
 *
 * @link https://stripe.com/docs/api/php#balance_transaction_object
 *
 * @property string     id
 * @property string     object// "balance_transaction"
 * @property int        amount
 * @property int        available_on
 * @property int        created
 * @property string     currency
 * @property int        fee
 * @property ListObject fee_details
 * @property int        net
 * @property string     status
 * @property string     type
 * @property string     description
 * @property string     source
 */
class BalanceTransaction extends Resource implements BalanceTransactionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The class URL for this resource. It needs to be special cased because
     * it doesn't fit into the standard resource pattern.
     *
     * @param  string $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return "/v1/balance/history";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieving a Balance Transaction
     * @link https://stripe.com/docs/api/php#retrieve_balance_transaction
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return BalanceTransaction
     */
    public static function retrieve($id, $options = null)
    {
        return parent::scopedRetrieve($id, $options);
    }

    /**
     * List balance history
     * @link https://stripe.com/docs/api/php#balance_history
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject
     */
    public static function all($params = [], $options = null)
    {
        return parent::scopedAll($params, $options);
    }
}
