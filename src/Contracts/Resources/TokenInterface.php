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
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Token.
     * @link   https://stripe.com/docs/api/php#retrieve_token
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Token.
     * @link   https://stripe.com/docs/api/php#create_card_token
     * @link   https://stripe.com/docs/api/php#create_bank_account_token
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);
}
