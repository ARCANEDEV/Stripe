<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface TokenInterface
{
    /**
     * @param string      $id The ID of the token to retrieve.
     * @param string|null $apiKey
     *
     * @return TokenInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return TokenInterface The created token.
     */
    public static function create($params = null, $apiKey = null);
}
