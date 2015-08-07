<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\ApplicationFeeRefund;

/**
 * Interface ApplicationFeeRefundInterface
 * @package Arcanedev\Stripe\Contracts\Resources
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
     * @param  array|string|null $options
     *
     * @return ApplicationFeeRefund
     */
    public function save($options = null);
}
