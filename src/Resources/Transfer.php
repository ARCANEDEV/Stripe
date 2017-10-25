<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\Transfer as TransferContract;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Transfer
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#transfer_object
 *
 * @property  string                            id
 * @property  string                            object                // 'transfer'
 * @property  int                               amount
 * @property  int                               amount_reversed
 * @property  string                            balance_transaction
 * @property  int                               created               // timestamp
 * @property  string                            currency
 * @property  int                               date                  // timestamp
 * @property  string                            destination
 * @property  string                            destination_payment
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  \Arcanedev\Stripe\Collection      reversals
 * @property  bool                              reversed
 * @property  string                            source_transaction
 */
class Transfer extends StripeResource implements TransferContract
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const PATH_REVERSALS = '/reversals';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * List all Transfers.
     * @link   https://stripe.com/docs/api/php#list_transfers
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Transfer.
     * @link   https://stripe.com/docs/api/php#retrieve_transfer
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create a transfer.
     * @link   https://stripe.com/docs/api/php#create_transfer
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update a Transfer.
     * @link   https://stripe.com/docs/api/php#update_transfer
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Update/Save a Transfer.
     * @link   https://stripe.com/docs/api/php#update_transfer
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }

    /**
     * Cancel a Transfer.
     * @link   https://stripe.com/docs/api/php#cancel_transfer
     *
     * @return self
     */
    public function cancel()
    {
        list($response, $opts) = $this->request('post', $this->instanceUrl().'/cancel');
        $this->refreshFrom($response, $opts);

        return $this;
    }

    /**
     * Created transfer reversal.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Resources\TransferReversal
     */
    public function reverse($params = [], $options = null)
    {
        list($response, $opts) = $this->request(
            'post', $this->instanceUrl().'/reversals', $params, $options
        );
        $this->refreshFrom($response, $opts);

        return $this;
    }

    /**
     * Create a transfer reversal.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Resources\TransferReversal
     */
    public static function createReversal($id, $params = null, $options = null)
    {
        return self::createNestedResource($id, static::PATH_REVERSALS, $params, $options);
    }

    /**
     * Retrieve a transfer reversal.
     *
     * @param  string             $id
     * @param  string             $reversalId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Resources\TransferReversal
     */
    public static function retrieveReversal($id, $reversalId, $params = null, $options = null)
    {
        return self::retrieveNestedResource($id, static::PATH_REVERSALS, $reversalId, $params, $options);
    }

    /**
     * Update a transfer reversal.
     *
     * @param  string             $id
     * @param  string             $reversalId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Resources\TransferReversal
     */
    public static function updateReversal($id, $reversalId, $params = null, $options = null)
    {
        return self::updateNestedResource($id, static::PATH_REVERSALS, $reversalId, $params, $options);
    }

    /**
     * List all the transfer reversals.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection
     */
    public static function allReversals($id, $params = null, $options = null)
    {
        return self::allNestedResources($id, static::PATH_REVERSALS, $params, $options);
    }
}
