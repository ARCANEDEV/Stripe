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
     *
     * @param  string             $country
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($country, $options = null);

    /**
     * Lists all Country Specs.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null);
}
