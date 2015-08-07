<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface BitcoinTransactionInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
interface BitcoinTransactionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The class URL for this resource.
     * It needs to be special cased because it doesn't fit into the standard resource pattern.
     *
     * @param  string $class Ignored.
     *
     * @return string
     */
    public static function classUrl($class = '');
}
