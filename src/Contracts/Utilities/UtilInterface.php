<?php namespace Arcanedev\Stripe\Contracts\Utilities;

use Arcanedev\Stripe\Collection;

interface UtilInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Recursively converts the PHP Stripe object to an array.
     *
     * @param  array $values The PHP Stripe object to convert.
     *
     * @return array
     */
    public static function convertStripeObjectToArray($values);

    /**
     * Converts a response from the Stripe API to the corresponding PHP object.
     *
     * @param  array  $response   - The response from the Stripe API.
     * @param  string $options
     *
     * @return \Arcanedev\Stripe\Object|Resource|Collection|array
     */
    public static function convertToStripeObject($response, $options);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param  array|mixed $array
     *
     * @return boolean True if the given object is a list.
     */
    public static function isList($array);
}
