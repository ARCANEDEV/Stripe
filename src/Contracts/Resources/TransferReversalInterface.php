<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  TransferReversalInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TransferReversalInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Saved the transfer reversal.
     * @link   https://stripe.com/docs/api/php#update_transfer_reversal
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);
}
