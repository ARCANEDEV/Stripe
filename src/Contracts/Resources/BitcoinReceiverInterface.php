<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  BitcoinReceiverInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface BitcoinReceiverInterface
{
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
    public static function retrieve($id, $options = null);

    /**
     * List all Bitcoin Receivers.
     * @link   https://stripe.com/docs/api/php#list_bitcoin_receivers
     *
     * @param  array|null   $params
     * @param  string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create Bitcoin Receiver Object.
     * @link  https://stripe.com/docs/api/php#create_bitcoin_receiver
     *
     * @param  array|null   $params
     * @param  string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Save Bitcoin Receiver Object.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Refund the Bitcoin Receiver item.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function refund($params = [], $options = null);
}
