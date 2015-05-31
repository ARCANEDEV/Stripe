<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\BitcoinTransactionInterface;
use Arcanedev\Stripe\Resource;

/**
 * Class BitcoinTransaction
 * @package Arcanedev\Stripe\Resources
 */
class BitcoinTransaction extends Resource implements BitcoinTransactionInterface
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
    public static function classUrl($class = '')
    {
        return "/v1/bitcoin/transactions";
    }
}
