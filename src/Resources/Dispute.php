<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\DisputeInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Dispute
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#dispute_object
 *
 * @property  string                                       id
 * @property  string                                       object                // "dispute"
 * @property  int                                          amount
 * @property  array                                        balance_transactions
 * @property  string                                       charge
 * @property  int                                          created               // timestamp
 * @property  string                                       currency
 * @property  \Arcanedev\Stripe\AttachedObject             evidence
 * @property  \Arcanedev\Stripe\StripeObject               evidence_details
 * @property  bool                                         is_charge_refundable
 * @property  bool                                         livemode
 * @property  \Arcanedev\Stripe\AttachedObject             metadata
 * @property  string                                       reason
 * @property  string                                       status
 */
class Dispute extends StripeResource implements DisputeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all Disputes.
     * @link   https://stripe.com/docs/api/php#list_disputes
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
     * Get a Dispute by id.
     * @link   https://stripe.com/docs/api/php#retrieve_dispute
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
     * Update a Dispute.
     * @link   https://stripe.com/docs/api/php#update_dispute
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
     * Save a Dispute.
     * @link   https://stripe.com/docs/api/php#update_dispute
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
     * Close a Dispute.
     * @link   https://stripe.com/docs/api/php#close_dispute
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function close($options = null)
    {
        $url = $this->instanceUrl() . '/close';

        return self::scopedPostCall($url, [], $options);
    }
}
