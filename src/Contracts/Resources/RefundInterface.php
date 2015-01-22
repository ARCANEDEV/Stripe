<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Refund;

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
     * @return Refund
     */
    public function save();
}
