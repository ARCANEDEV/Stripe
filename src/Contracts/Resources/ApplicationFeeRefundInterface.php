<?php namespace Arcanedev\Stripe\Contracts\Resources;
use Arcanedev\Stripe\Contracts\AttachedObjectInterface;

/**
 * Application Fee Refund Object Interface
 * @link https://stripe.com/docs/api/php#fee_refunds
 *
 * @property string                  id
 * @property string                  object  // "fee_refund"
 * @property int                     amount
 * @property int                     created
 * @property string                  currency
 * @property string                  balance_transaction
 * @property string                  fee
 * @property AttachedObjectInterface metadata
 */
interface ApplicationFeeRefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update/Save an Application Fee Refund
     * @link https://stripe.com/docs/api/php#update_fee_refund
     *
     * @return ApplicationFeeRefundInterface
     */
    public function save();
}
