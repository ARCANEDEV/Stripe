<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\TokenInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Token
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#token_object
 *
 * @property  string                                   id
 * @property  string                                   object     // 'token'
 * @property  \Arcanedev\Stripe\Resources\BankAccount  bank_account
 * @property  \Arcanedev\Stripe\Resources\Card         card
 * @property  string                                   client_ip
 * @property  int                                      created    // timestamp
 * @property  bool                                     livemode
 * @property  string                                   type       // 'card' or 'bank_account'
 * @property  bool                                     used
 */
class Token extends StripeResource implements TokenInterface
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
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

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
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }
}
