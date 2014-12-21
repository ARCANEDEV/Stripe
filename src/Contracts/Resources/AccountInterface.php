<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface AccountInterface
{
    /**
     * @param string|null $apiKey
     *
     * @return AccountInterface
     */
    public static function retrieve($apiKey = null);
}
