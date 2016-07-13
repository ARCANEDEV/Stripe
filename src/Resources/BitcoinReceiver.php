<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Bases\ExternalAccount;
use Arcanedev\Stripe\Contracts\Resources\BitcoinReceiverInterface;

/**
 * Class     BitcoinReceiver
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#bitcoin_receivers
 *
 * @property  string                            id
 * @property  string                            object
 * @property  bool                              active
 * @property  int                               amount
 * @property  int                               amount_received
 * @property  int                               bitcoin_amount
 * @property  int                               bitcoin_amount_received
 * @property  string                            bitcoin_uri
 * @property  int                               created
 * @property  string                            currency
 * @property  Customer                          customer
 * @property  string                            description
 * @property  string                            email
 * @property  bool                              filled
 * @property  string                            inbound_address
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            payment
 * @property  string                            refund_address
 * @property  \Arcanedev\Stripe\Collection      transactions
 * @property  bool                              uncaptured_funds
 * @property  bool                              used_for_payment
 */
class BitcoinReceiver extends ExternalAccount implements BitcoinReceiverInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return string The instance URL for this resource. It needs to be special
     *    cased because it doesn't fit into the standard resource pattern.
     */
    public function instanceUrl()
    {
        if ( ! is_null($result = parent::instanceUrl())) {
            return $result;
        }

        $base   = self::classUrl();
        $extn   = urlencode(str_utf8($this['id']));

        return "$base/$extn";
    }

    /**
     * The class URL for this resource.
     * It needs to be special cased because it does not fit into the standard resource pattern.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return '/v1/bitcoin/receivers';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve Bitcoin Receiver.
     * @link   https://stripe.com/docs/api/php#retrieve_bitcoin_receiver
     *
     * @param  string       $id
     * @param  string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * List all Bitcoin Receivers.
     * @link   https://stripe.com/docs/api/php#list_bitcoin_receivers
     *
     * @param  array|null   $params
     * @param  string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Create Bitcoin Receiver Object.
     * @link   https://stripe.com/docs/api/php#create_bitcoin_receiver
     *
     * @param  array|null   $params
     * @param  string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Refund the Bitcoin Receiver item.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function refund($params = [], $options = null)
    {
        list($response, $opts) = $this->request(
            'post', $this->instanceUrl() . '/refund', $params, $options
        );

        $this->refreshFrom($response, $opts);

        return $this;
    }
}
