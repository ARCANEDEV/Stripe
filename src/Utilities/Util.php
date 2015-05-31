<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Utilities\UtilInterface;
use Arcanedev\Stripe\Object;

/**
 * Class Util
 * @package Arcanedev\Stripe\Utilities
 */
abstract class Util implements UtilInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const DEFAULT_RESOURCE = 'Arcanedev\\Stripe\\Object';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Available Resources
     *
     * @var array
     */
    private static $resources = [
        // Resource Objects
        'account'               => 'Arcanedev\\Stripe\\Resources\\Account',
        'balance_transaction'   => 'Arcanedev\\Stripe\\Resources\\BalanceTransaction',
        'card'                  => 'Arcanedev\\Stripe\\Resources\\Card',
        'charge'                => 'Arcanedev\\Stripe\\Resources\\Charge',
        'coupon'                => 'Arcanedev\\Stripe\\Resources\\Coupon',
        'customer'              => 'Arcanedev\\Stripe\\Resources\\Customer',
        'discount'              => 'Arcanedev\\Stripe\\Resources\\Discount',
        'dispute'               => 'Arcanedev\\Stripe\\Resources\\Dispute',
        'event'                 => 'Arcanedev\\Stripe\\Resources\\Event',
        'fee_refund'            => 'Arcanedev\\Stripe\\Resources\\ApplicationFeeRefund',
        'file_upload'           => 'Arcanedev\\Stripe\\Resources\\FileUpload',
        'invoice'               => 'Arcanedev\\Stripe\\Resources\\Invoice',
        'invoiceitem'           => 'Arcanedev\\Stripe\\Resources\\InvoiceItem',
        'plan'                  => 'Arcanedev\\Stripe\\Resources\\Plan',
        'recipient'             => 'Arcanedev\\Stripe\\Resources\\Recipient',
        'refund'                => 'Arcanedev\\Stripe\\Resources\\Refund',
        'subscription'          => 'Arcanedev\\Stripe\\Resources\\Subscription',
        'token'                 => 'Arcanedev\\Stripe\\Resources\\Token',
        'transfer'              => 'Arcanedev\\Stripe\\Resources\\Transfer',
        'transfer_reversal'     => 'Arcanedev\\Stripe\\Resources\\TransferReversal',
        'bitcoin_receiver'      => 'Arcanedev\\Stripe\\Resources\\BitcoinReceiver',
        'bitcoin_transaction'   => 'Arcanedev\\Stripe\\Resources\\BitcoinTransaction',

        // List Object
        'list'                  => 'Arcanedev\\Stripe\\Collection',
    ];

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
    public static function convertStripeObjectToArray($values)
    {
        $results = [];

        foreach ($values as $k => $v) {
            // FIXME: this is an encapsulation violation
            if ($k[0] == '_') {
                continue;
            }

            if ($v instanceof Object) {
                $results[$k] = $v->toArray(true);
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
     * @param  array $response   - The response from the Stripe API.
     * @param  array $options
     *
     * @return \Arcanedev\Stripe\Object|Resource|Collection|array
     */
    public static function convertToStripeObject($response, $options)
    {
        if (self::isList($response)) {
            $mapped = array_map(function($i) use ($options) {
                return self::convertToStripeObject($i, $options);
            }, $response);

            return $mapped;
        }
        elseif (is_array($response)) {
            $class = self::getClassTypeObject($response);

            return Object::scopedConstructFrom($class, $response, $options);
        }

        return $response;
    }

    /**
     * Get Class Type
     *
     * @param  array $response
     *
     * @return string
     */
    private static function getClassTypeObject($response)
    {
        if (self::isClassTypeObjectExist($response)) {
            $object = $response['object'];
            return self::getClassTypeFromAvailableResources($object);
        }

        return self::DEFAULT_RESOURCE;
    }

    /**
     * Get Class Type from available resources
     *
     * @param  string $object
     *
     * @return string
     */
    private static function getClassTypeFromAvailableResources($object)
    {
        return self::$resources[$object];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param  mixed $array
     *
     * @return boolean True if the given object is a list.
     */
    public static function isList($array)
    {
        if (! is_array($array)) {
            return false;
        }

        // TODO: generally incorrect, but it's correct given Stripe's response
        foreach (array_keys($array) as $k) {
            if (! is_numeric($k)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array $response
     *
     * @return bool
     */
    private static function isClassTypeObjectExist($response)
    {
        if (
            isset($response['object'])
            and is_string($response['object'])
        ) {
            return self::isInAvailableResources($response['object']);
        }

        return false;
    }

    /**
     * @param  string $object
     *
     * @return bool
     */
    private static function isInAvailableResources($object)
    {
        return array_key_exists($object, self::$resources);
    }
}
