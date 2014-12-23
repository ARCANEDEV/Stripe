<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\TokenInterface;
use Arcanedev\Stripe\Resource;

/**
 * Token Object
 * @link https://stripe.com/docs/api/curl#token_object
 *
 * @property string id
 * @property string object  // "token"
 * @property bool   livemode
 * @property int    created
 * @property string type  // ['card'|'bank_account']
 * @property bool   used
 * @property Object bank_account
 * @property Card   card
 */
class Token extends Resource implements TokenInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Token
     * @link https://stripe.com/docs/api/curl#retrieve_token
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Token
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve(get_class(), $id, $apiKey);
    }

    /**
     * Create a Card Token
     * @link https://stripe.com/docs/api/curl#create_card_token
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Token
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate(get_class(), $params, $apiKey);
    }
}
