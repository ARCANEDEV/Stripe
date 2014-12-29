<?php namespace Arcanedev\Stripe\Contracts;

interface ObjectInterface
{
    /**
     * @return array
     */
    public function keys();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * This unfortunately needs to be public to be used in Util.php
     *
     * @param string      $class
     * @param array       $values
     * @param string|null $apiKey
     *
     * @return Object The object constructed from the given values.
     */
    public static function scopedConstructFrom($class, $values, $apiKey = null);

    /**
     * Refreshes this object using the provided values.
     *
     * @param array   $values
     * @param string  $apiKey
     * @param boolean $partial Defaults to false.
     */
    public function refreshFrom($values, $apiKey, $partial = false);
}
