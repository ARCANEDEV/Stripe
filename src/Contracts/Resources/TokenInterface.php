<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Token Object Interface
 * @link https://stripe.com/docs/api/curl#token_object
 *
 * @property string        id
 * @property string        object  // "token"
 * @property bool          livemode
 * @property int           created
 * @property string        type  // ['card'|'bank_account']
 * @property bool          used
 * @property Object        bank_account
 * @property CardInterface card
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
     * @param string            $id
     * @param array|string|null $options
     *
     * @return TokenInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a Card Token
     * @link https://stripe.com/docs/api/curl#create_card_token
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return TokenInterface
     */
    public static function create($params = [], $options = null);
}
