<?php namespace Arcanedev\Stripe\Contracts\Resources;

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
