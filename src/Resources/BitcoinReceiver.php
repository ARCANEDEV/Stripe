<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Bases\ExternalAccount;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\BitcoinReceiverInterface;

/**
 * Class     BitcoinReceiver
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @link     https://stripe.com/docs/api/php#bitcoin_receivers
 *
 * @property  string                            id
 * @property  string                            object
 * @property  int                               created
 * @property  bool                              livemode
 * @property  bool                              active
 * @property  int                               amount
 * @property  int                               amount_received
 * @property  int                               bitcoin_amount
 * @property  int                               bitcoin_amount_received
 * @property  string                            bitcoin_uri
 * @property  string                            currency
 * @property  bool                              filled
 * @property  string                            inbound_address
 * @property  bool                              uncaptured_funds
 * @property  string                            description
 * @property  string                            email
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            refund_address
 * @property  bool                              used_for_payment
 * @property  Customer                          customer
 * @property  string                            payment
 * @property  Collection                        transactions
 */
class BitcoinReceiver extends ExternalAccount implements BitcoinReceiverInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return string The instance URL for this resource. It needs to be special
     *    cased because it doesn't fit into the standard resource pattern.
     */
    public function instanceUrl()
    {
        $result = parent::instanceUrl();

        if ($result === null) {
            $extn   = urlencode(str_utf8($this['id']));
            $base   = self::classUrl();

            $result = "$base/$extn";
        }

        return $result;
    }

    /**
     * The class URL for this resource.
     * It needs to be special cased because it doesn't fit into the standard resource pattern.
     *
     * @param  string $class Ignored.
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return '/v1/bitcoin/receivers';
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve Bitcoin Receiver
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
     * List all Bitcoin Receivers
     *
     * @param  array|null   $params
     * @param  string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Create Bitcoin Receiver Object
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
    public function refund($params = null, $options = null)
    {
        $url                   = $this->instanceUrl() . '/refund';
        list($response, $opts) = $this->request('post', $url, $params, $options);

        $this->refreshFrom($response, $opts);

        return $this;
    }
}
