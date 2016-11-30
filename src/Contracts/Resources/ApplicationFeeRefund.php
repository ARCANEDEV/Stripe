<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  ApplicationFeeRefund
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ApplicationFeeRefund
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update/Save an Application Fee Refund.
     *
     * @link   https://stripe.com/docs/api/php#update_fee_refund
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);
}
