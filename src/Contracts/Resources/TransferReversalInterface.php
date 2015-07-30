<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\TransferReversal;

/**
 * Interface TransferReversalInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
interface TransferReversalInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Saved the transfer reversal
     *
     * @param  array|string|null $options
     *
     * @return TransferReversal
     */
    public function save($options = null);
}
