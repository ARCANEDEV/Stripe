<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\BalanceTransaction;

interface BalanceTransactionInterface
{
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
    public static function retrieve($id, $options = null);

    /**
     * List balance history
     * @link https://stripe.com/docs/api/php#balance_history
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject
     */
    public static function all($params = [], $options = null);
}
