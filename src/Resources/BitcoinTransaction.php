<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\BitcoinTransaction as BitcoinTransactionContract;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     BitcoinTransaction
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BitcoinTransaction extends StripeResource implements BitcoinTransactionContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
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
    public static function classUrl($class = '')
    {
        return '/v1/bitcoin/transactions';
    }
}
