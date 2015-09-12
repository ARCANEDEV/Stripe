<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  TokenInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TokenInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Token.
     *
     * @link   https://stripe.com/docs/api/curl#retrieve_token
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Card Token.
     *
     * @link   https://stripe.com/docs/api/curl#create_card_token
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);
}
