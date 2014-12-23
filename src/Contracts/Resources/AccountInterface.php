<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Account Object Interface
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
interface AccountInterface
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
     * @return AccountInterface
     */
    public static function retrieve($apiKey = null);
}
