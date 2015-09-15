<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Utilities\UtilInterface;
use Arcanedev\Stripe\StripeObject;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Util
 *
 * @package  Arcanedev\Stripe\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Util implements UtilInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const DEFAULT_NAMESPACE = 'Arcanedev\\Stripe\\';

    const DEFAULT_RESOURCE  = 'StripeObject';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Available Resources.
     *
     * @var array
     */
    private static $resources = [
        'account'               => 'Resources\\Account',
        'alipay_account'        => 'Resources\\AlipayAccount',
        'balance_transaction'   => 'Resources\\BalanceTransaction',
        'bank_account'          => 'Resources\\BankAccount',
        'bitcoin_receiver'      => 'Resources\\BitcoinReceiver',
        'bitcoin_transaction'   => 'Resources\\BitcoinTransaction',
        'card'                  => 'Resources\\Card',
        'charge'                => 'Resources\\Charge',
        'coupon'                => 'Resources\\Coupon',
        'customer'              => 'Resources\\Customer',
        'discount'              => 'Resources\\Discount',
        'dispute'               => 'Resources\\Dispute',
        'event'                 => 'Resources\\Event',
        'fee_refund'            => 'Resources\\ApplicationFeeRefund',
        'file_upload'           => 'Resources\\FileUpload',
        'invoice'               => 'Resources\\Invoice',
        'invoiceitem'           => 'Resources\\InvoiceItem',
        'list'                  => 'Collection',                     // List Object
        'order'                 => 'Resources\\Order',
        'plan'                  => 'Resources\\Plan',
        'product'               => 'Resources\\Product',
        'recipient'             => 'Resources\\Recipient',
        'refund'                => 'Resources\\Refund',
        'sku'                   => 'Resources\\Sku',
        'subscription'          => 'Resources\\Subscription',
        'token'                 => 'Resources\\Token',
        'transfer'              => 'Resources\\Transfer',
        'transfer_reversal'     => 'Resources\\TransferReversal',
    ];

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
    public static function convertStripeObjectToArray($values)
    {
        $results = [];

        foreach ($values as $k => $v) {
            // FIXME: this is an encapsulation violation
            if ($k[0] == '_') {
                continue;
            }

            if ($v instanceof StripeObject) {
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
     * @param  array  $response
     * @param  array  $options
     *
     * @return StripeObject|StripeResource|Collection|array
     */
    public static function convertToStripeObject($response, $options)
    {
        if (self::isList($response)) {
            return array_map(function($i) use ($options) {
                return self::convertToStripeObject($i, $options);
            }, $response);
        }
        elseif (is_array($response)) {
            return StripeObject::scopedConstructFrom(
                self::DEFAULT_NAMESPACE . self::getClassTypeObject($response),
                $response,
                $options
            );
        }

        return $response;
    }

    /**
     * Get Class Type.
     *
     * @param  array  $response
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
     * Get Class Type from available resources.
     *
     * @param  string  $object
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
     * @param  mixed  $array
     *
     * @return bool
     */
    public static function isList($array)
    {
        if ( ! is_array($array)) return false;

        // TODO: generally incorrect, but it's correct given Stripe's response
        foreach (array_keys($array) as $k) {
            if ( ! is_numeric($k)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the object is a resource.
     *
     * @param  array  $response
     *
     * @return bool
     */
    private static function isClassTypeObjectExist($response)
    {
        if (isset($response['object']) && is_string($response['object'])) {
            return self::isInAvailableResources($response['object']);
        }

        return false;
    }

    /**
     * Check is an available resource.
     *
     * @param  string $object
     *
     * @return bool
     */
    private static function isInAvailableResources($object)
    {
        return array_key_exists($object, self::$resources);
    }
}
