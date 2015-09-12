<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\DisputeInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Dispute
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#dispute_object
 *
 * @property string          object  // "dispute"
 * @property bool            livemode
 * @property int             amount
 * @property string          charge
 * @property int             created
 * @property string          currency
 * @property string          reason
 * @property string          status
 * @property array           balance_transactions
 * @property DisputeEvidence evidence
 * @property array|null      evidence_details
 * @property bool            is_charge_refundable
 * @property AttachedObject  metadata
 *
 * @todo:    Update the properties.
 */
class Dispute extends StripeResource implements DisputeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get a dispute by id.
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
     * Get all disputes.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Save dispute.
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
     * Close dispute.
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
