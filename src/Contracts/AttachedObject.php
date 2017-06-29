<?php namespace Arcanedev\Stripe\Contracts;

use Countable;

/**
 * Interface  AttachedObject
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface AttachedObject extends Countable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Updates this object.
     * A mapping of properties to update on this object.
     *
     * @param  array  $properties
     */
    public function replaceWith($properties);
}
