<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Utilities\Util as UtilContract;
use Arcanedev\Stripe\Resources;
use Arcanedev\Stripe\StripeObject;

/**
 * Class     Util
 *
 * @package  Arcanedev\Stripe\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Util implements UtilContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Available Resources.
     *
     * @var array
     */
    private static $resources = [
        // Data structures
        'list'                => Collection::class,

        // Resources
        'account'             => Resources\Account::class,
        'alipay_account'      => Resources\AlipayAccount::class,
        'apple_pay_domain'    => Resources\ApplePayDomain::class,
        'balance_transaction' => Resources\BalanceTransaction::class,
        'bank_account'        => Resources\BankAccount::class,
        'bitcoin_transaction' => Resources\BitcoinTransaction::class,
        'card'                => Resources\Card::class,
        'charge'              => Resources\Charge::class,
        'country_spec'        => Resources\CountrySpec::class,
        'coupon'              => Resources\Coupon::class,
        'customer'            => Resources\Customer::class,
        'discount'            => Resources\Discount::class,
        'dispute'             => Resources\Dispute::class,
        'event'               => Resources\Event::class,
        'ephemeral_key'       => Resources\EphemeralKey::class,
        'exchange_rate'       => Resources\ExchangeRate::class,
        'fee_refund'          => Resources\ApplicationFeeRefund::class,
        'file_upload'         => Resources\FileUpload::class,
        'invoice'             => Resources\Invoice::class,
        'invoiceitem'         => Resources\InvoiceItem::class,
        'login_link'          => Resources\LoginLink::class,
        'order'               => Resources\Order::class,
        'order_item'          => Resources\OrderItem::class,
        'order_return'        => Resources\OrderReturn::class,
        'payout'              => Resources\Payout::class,
        'plan'                => Resources\Plan::class,
        'product'             => Resources\Product::class,
        'recipient'           => Resources\Recipient::class,
        'recipient_transfer'  => Resources\RecipientTransfer::class,
        'refund'              => Resources\Refund::class,
        'sku'                 => Resources\Sku::class,
        'source'              => Resources\Source::class,
        'source_transaction'  => Resources\SourceTransaction::class,
        'subscription'        => Resources\Subscription::class,
        'subscription_item'   => Resources\SubscriptionItem::class,
        'three_d_secure'      => Resources\ThreeDSecure::class,
        'token'               => Resources\Token::class,
        'transfer'            => Resources\Transfer::class,
        'transfer_reversal'   => Resources\TransferReversal::class,
    ];

    private static $isHashEqualsAvailable = null;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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
            // TODO: Fix the encapsulation violation
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
            $class = self::getClassTypeObject($response);

            return $class::scopedConstructFrom(
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
            : StripeObject::class;
    }

    /**
     * Compares two strings for equality. The time taken is independent of the
     * number of characters that match.
     *
     * @param  string  $one
     * @param  string  $two
     *
     * @return bool
     */
    public static function secureCompare($one, $two)
    {
        if (self::$isHashEqualsAvailable === null) {
            self::$isHashEqualsAvailable = function_exists('hash_equals');
        }

        if (self::$isHashEqualsAvailable) {
            return hash_equals($one, $two);
        }

        if (strlen($one) != strlen($two)) {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < strlen($one); $i++) {
            $result |= ord($one[$i]) ^ ord($two[$i]);
        }

        return ($result == 0);
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
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
