<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\TokenInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class Token
 * @package Arcanedev\Stripe\Resources
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
class Token extends StripeResource implements TokenInterface
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
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create a Card Token
     * @link https://stripe.com/docs/api/curl#create_card_token
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }
}
