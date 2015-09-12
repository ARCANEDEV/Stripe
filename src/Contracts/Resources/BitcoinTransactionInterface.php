<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  BitcoinTransactionInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * @param  string  $class
     *
     * @return string
     */
    public static function classUrl($class = '');
}
