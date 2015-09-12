<?php namespace Arcanedev\Stripe\Contracts;

/**
 * Interface  AttachedObjectInterface
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface AttachedObjectInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Updates this object.
     * A mapping of properties to update on this object.
     *
     * @param  array  $properties
     */
    public function replaceWith($properties);
}
