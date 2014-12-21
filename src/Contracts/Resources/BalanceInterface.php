<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface BalanceInterface
{
    /**
     * @param string|null $apiKey
     *
     * @return BalanceInterface
     */
    public static function retrieve($apiKey = null);
}
