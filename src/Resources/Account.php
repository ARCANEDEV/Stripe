<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\AccountInterface;
use Arcanedev\Stripe\SingletonResource;

/**
 * Account Object
 * @link https://stripe.com/docs/api/php#account_object
 *
 * @property null   id
 * @property string object
 * @property bool   charges_enabled
 * @property string country
 * @property array  currencies_supported
 * @property string default_currency
 * @property bool   details_submitted
 * @property bool   transfers_enabled
 * @property string display_name
 * @property string email
 * @property string statement_descriptor
 * @property string timezone
 */
class Account extends SingletonResource implements AccountInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an account
     * @link https://stripe.com/docs/api/php#retrieve_account
     *
     * @param string|null $apiKey
     *
     * @return Account
     */
    public static function retrieve($apiKey = null)
    {
        return self::scopedSingletonRetrieve(get_class(), $apiKey);
    }
}
