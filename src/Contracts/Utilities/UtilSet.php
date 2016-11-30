<?php namespace Arcanedev\Stripe\Contracts\Utilities;

/**
 * Interface  UtilSet
 *
 * @package   Arcanedev\Stripe\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface UtilSet
{
    /**
     * Check if attribute is included.
     *
     * @param  string  $attribute
     *
     * @return bool
     */
    public function includes($attribute);
}
