<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Contracts\Utilities\UtilInterface;
use Arcanedev\Stripe\StripeObject;

/**
 * Class     Util
 *
 * @package  Arcanedev\Stripe\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Util implements UtilInterface
{
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
        'account'             => 'Arcanedev\\Stripe\\Resources\\Account',
        'alipay_account'      => 'Arcanedev\\Stripe\\Resources\\AlipayAccount',
        'apple_pay_domain'    => 'Arcanedev\\Stripe\\Resources\\ApplePayDomain',
        'balance_transaction' => 'Arcanedev\\Stripe\\Resources\\BalanceTransaction',
        'bank_account'        => 'Arcanedev\\Stripe\\Resources\\BankAccount',
        'bitcoin_receiver'    => 'Arcanedev\\Stripe\\Resources\\BitcoinReceiver',
        'bitcoin_transaction' => 'Arcanedev\\Stripe\\Resources\\BitcoinTransaction',
        'card'                => 'Arcanedev\\Stripe\\Resources\\Card',
        'charge'              => 'Arcanedev\\Stripe\\Resources\\Charge',
        'country_spec'        => 'Arcanedev\\Stripe\\Resources\\CountrySpec',
        'coupon'              => 'Arcanedev\\Stripe\\Resources\\Coupon',
        'customer'            => 'Arcanedev\\Stripe\\Resources\\Customer',
        'discount'            => 'Arcanedev\\Stripe\\Resources\\Discount',
        'dispute'             => 'Arcanedev\\Stripe\\Resources\\Dispute',
        'event'               => 'Arcanedev\\Stripe\\Resources\\Event',
        'fee_refund'          => 'Arcanedev\\Stripe\\Resources\\ApplicationFeeRefund',
        'file_upload'         => 'Arcanedev\\Stripe\\Resources\\FileUpload',
        'invoice'             => 'Arcanedev\\Stripe\\Resources\\Invoice',
        'invoiceitem'         => 'Arcanedev\\Stripe\\Resources\\InvoiceItem',
        'list'                => 'Arcanedev\\Stripe\\Collection',                     // List Object
        'order'               => 'Arcanedev\\Stripe\\Resources\\Order',
        'order_item'          => 'Arcanedev\\Stripe\\Resources\\OrderItem',
        'order_return'        => 'Arcanedev\\Stripe\\Resources\\OrderReturn',
        'plan'                => 'Arcanedev\\Stripe\\Resources\\Plan',
        'product'             => 'Arcanedev\\Stripe\\Resources\\Product',
        'recipient'           => 'Arcanedev\\Stripe\\Resources\\Recipient',
        'refund'              => 'Arcanedev\\Stripe\\Resources\\Refund',
        'sku'                 => 'Arcanedev\\Stripe\\Resources\\Sku',
        'source'              => 'Arcanedev\\Stripe\\Resources\\Source',
        'subscription'        => 'Arcanedev\\Stripe\\Resources\\Subscription',
        'subscription_item'   => 'Arcanedev\\Stripe\\Resources\\SubscriptionItem',
        'three_d_secure'      => 'Arcanedev\\Stripe\\Resources\\ThreeDSecure',
        'token'               => 'Arcanedev\\Stripe\\Resources\\Token',
        'transfer'            => 'Arcanedev\\Stripe\\Resources\\Transfer',
        'transfer_reversal'   => 'Arcanedev\\Stripe\\Resources\\TransferReversal',
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
            if ($k[0] == '_') continue;

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
     * @return \Arcanedev\Stripe\StripeObject|\Arcanedev\Stripe\StripeResource|\Arcanedev\Stripe\Collection|array
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
                self::getClassTypeObject($response),
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
        return self::isClassTypeObjectExist($response)
            ? self::$resources[ $response['object'] ]
            : 'Arcanedev\\Stripe\\StripeObject';
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
            if ( ! is_numeric($k)) return false;
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
            return array_key_exists($response['object'], self::$resources);
        }

        return false;
    }
}
