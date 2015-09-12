<?php namespace Arcanedev\Stripe\Contracts\Utilities;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\StripeObject;
use Arcanedev\Stripe\StripeResource;

/**
 * Interface  UtilInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface UtilInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Recursively converts the PHP Stripe object to an array.
     *
     * @param  array  $values
     *
     * @return array
     */
    public static function convertStripeObjectToArray($values);

    /**
     * Converts a response from the Stripe API to the corresponding PHP object.
     *
     * @param  array   $response
     * @param  string  $options
     *
     * @return StripeObject|StripeResource|Collection|array
     */
    public static function convertToStripeObject($response, $options);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param  array|mixed  $array
     *
     * @return bool
     */
    public static function isList($array);
}
