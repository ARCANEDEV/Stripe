<?php namespace Arcanedev\Stripe;

abstract class Util
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private static $resources = [
        // Resource Object
        'card'          => 'Arcanedev\\Stripe\\Resources\\Card',
        'charge'        => 'Arcanedev\\Stripe\\Resources\\Charge',
        'coupon'        => 'Arcanedev\\Stripe\\Resources\\Coupon',
        'customer'      => 'Arcanedev\\Stripe\\Resources\\Customer',
        'invoice'       => 'Arcanedev\\Stripe\\Resources\\Invoice',
        'invoiceitem'   => 'Arcanedev\\Stripe\\Resources\\InvoiceItem',
        'event'         => 'Arcanedev\\Stripe\\Resources\\Event',
        'transfer'      => 'Arcanedev\\Stripe\\Resources\\Transfer',
        'plan'          => 'Arcanedev\\Stripe\\Resources\\Plan',
        'recipient'     => 'Arcanedev\\Stripe\\Resources\\Recipient',
        'refund'        => 'Arcanedev\\Stripe\\Resources\\Refund',
        'subscription'  => 'Arcanedev\\Stripe\\Resources\\Subscription',
        'fee_refund'    => 'Arcanedev\\Stripe\\Resources\\ApplicationFeeRefund',

        // List Object
        'list'          => 'Arcanedev\\Stripe\\ObjectList',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Recursively converts the PHP Stripe object to an array.
     *
     * @param array $values The PHP Stripe object to convert.
     *
     * @return array
     */
    public static function convertStripeObjectToArray($values)
    {
        $results = [];

        foreach ($values as $k => $v) {
            // FIXME: this is an encapsulation violation
            if ( $k[0] == '_' ) {
                continue;
            }

            if ($v instanceof Object) {
                $results[$k] = $v->__toArray(true);
            }
            elseif (is_array($v)) {
                $results[$k] = self::convertStripeObjectToArray($v);
            }
            else {
                $results[$k] = $v;
            }
        }

        return $results;
    }

    /**
     * Converts a response from the Stripe API to the corresponding PHP object.
     *
     * @param array  $response   - The response from the Stripe API.
     * @param string $apiKey
     *
     * @return Object|array
     */
    public static function convertToStripeObject($response, $apiKey)
    {
        if ( self::isList($response) ) {
            $mapped = [];

            foreach ($response as $i) {
                array_push($mapped, self::convertToStripeObject($i, $apiKey));
            }

            return $mapped;
        }
        elseif ( is_array($response) ) {
            $class = self::getClassTypeObject($response);

            return Object::scopedConstructFrom($class, $response, $apiKey);
        }

        return $response;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param array|mixed $array
     *
     * @return boolean True if the given object is a list.
     */
    public static function isList($array)
    {
        if ( ! is_array($array) ) {
            return false;
        }

        // TODO: generally incorrect, but it's correct given Stripe's response
        foreach (array_keys($array) as $k) {
            if ( ! is_numeric($k) ) {
                return false;
            }
        }

        return true;
    }

    private static function getClassTypeObject($response)
    {
        if ( self::isClassTypeObjectExist($response) ) {
            return self::$resources[$response['object']];
        }

        return 'Arcanedev\\Stripe\\Object';
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    private static function isClassTypeObjectExist($response)
    {
        return isset( $response['object'] ) and is_string( $response['object'] ) and
               isset( self::$resources[$response['object']] );
    }
}
