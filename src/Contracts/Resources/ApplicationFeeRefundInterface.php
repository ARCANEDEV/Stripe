<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\ApplicationFeeRefund;

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
     * @return ApplicationFeeRefund
     */
    public function save();
}
