<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\CountrySpecInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     CountrySpec
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#country_spec_object
 *
 * @property  string                          id
 * @property  string                          object                             // 'country_spec'
 * @property  \Arcanedev\Stripe\StripeObject  supported_bank_account_currencies
 * @property  array                           supported_payment_currencies
 * @property  array                           supported_payment_methods
 * @property  \Arcanedev\Stripe\StripeObject  verification_fields
 */
class CountrySpec extends StripeResource implements CountrySpecInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * This is a special case because the country specs endpoint has an underscore in it.
     * The parent `className` function strips underscores.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function className($class = '')
    {
        return 'country_spec';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the Country Spec for a given Country code.
     * @link   https://stripe.com/docs/api/php#retrieve_country_spec
     *
     * @param  string             $country
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($country, $options = null)
    {
        return self::scopedRetrieve($country, $options);
    }

    /**
     * Lists all Country Specs.
     * @link   https://stripe.com/docs/api/php#list_country_specs
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
