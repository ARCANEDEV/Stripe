<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\Payout as PayoutContract;
use Arcanedev\Stripe\StripeResource;

/**
 * Class Payout
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string                            id
 * @property  string                            object
 * @property  int                               amount
 * @property  string                            balance_transaction
 * @property  string                            cancellation_balance_transaction
 * @property  int                               created
 * @property  string                            currency
 * @property  int                               arrival_date
 * @property  string                            destination
 * @property  string                            failure_code
 * @property  string                            failure_message
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            method
 * @property  string                            recipient
 * @property  string                            source_type
 * @property  string                            statement_descriptor
 * @property  string                            status
 * @property  string                            type
 */
class Payout extends StripeResource implements PayoutContract
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Retrieve a payout.
     *
     * @param  string             $id
     * @param  array|string|null  $opts
     *
     * @return self
     */
    public static function retrieve($id, $opts = null)
    {
        return self::scopedRetrieve($id, $opts);
    }

    /**
     * List all the payouts.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Create the payout.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update the payout.
     *
     * @param  string            $id
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function update($id, $params = null, $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Cancel the payout.
     *
     * @return self
     */
    public function cancel()
    {
        list($response, $options) = $this->request('post', $this->instanceUrl().'/cancel');
        $this->refreshFrom($response, $options);

        return $this;
    }

    /**
     * Save the payout.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }
}