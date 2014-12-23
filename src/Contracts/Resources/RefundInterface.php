<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;

/**
 * Refund Object Interface
 * @link https://stripe.com/docs/api/php#refund_object
 *
 * @property string                  id
 * @property string                  object  // "refund"
 * @property int                     amount
 * @property int                     created
 * @property string                  currency
 * @property string                  balance_transaction
 * @property string                  charge
 * @property AttachedObjectInterface metadata
 * @property string                  reason
 * @property string                  receipt_number
 * @property string                  description
 */
interface RefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update/Save a Refund
     * @link https://stripe.com/docs/api/php#update_refund
     *
     * @return RefundInterface
     */
    public function save();
}
