<?php namespace Arcanedev\Stripe\Contracts;

/**
 * Interface  ObjectInterface
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ObjectInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get keys.
     *
     * @return array
     */
    public function keys();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * This unfortunately needs to be public to be used in Util.php
     * Return The object constructed from the given values.
     *
     * @param  string  $class
     * @param  array   $values
     * @param  string  $options
     *
     * @return \Arcanedev\Stripe\StripeObject
     */
    public static function scopedConstructFrom($class, $values, $options);

    /**
     * Refreshes this object using the provided values.
     *
     * @param  array    $values
     * @param  string   $apiKey
     * @param  bool     $partial
     */
    public function refreshFrom($values, $apiKey, $partial = false);
}
