<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Token;

/**
 * Interface TokenInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
interface TokenInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Token
     * @link https://stripe.com/docs/api/curl#retrieve_token
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Token
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Card Token
     * @link https://stripe.com/docs/api/curl#create_card_token
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Token|array
     */
    public static function create($params = [], $options = null);
}
