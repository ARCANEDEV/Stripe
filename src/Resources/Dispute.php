<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Resource;

/**
 * Class Dispute
 * @package Arcanedev\Stripe\Resources
 * @link https://stripe.com/docs/api/php#dispute_object
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
 */
class Dispute extends Resource
{
    // TODO: Complete Discount Class implementation
}
