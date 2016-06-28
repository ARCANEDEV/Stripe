<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  CountrySpecInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CountrySpecInterface
{
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
    public static function retrieve($country, $options = null);

    /**
     * Lists all Country Specs.
     * @link   https://stripe.com/docs/api/php#list_country_specs
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);
}
