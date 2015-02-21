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
     * Return The object constructed from the given values.
     *
     * @param  string $class
     * @param  array  $values
     * @param  string $options
     *
     * @return \Arcanedev\Stripe\Object
     */
    public static function scopedConstructFrom($class, $values, $options);

    /**
     * Refreshes this object using the provided values.
     *
     * @param array   $values
     * @param string  $apiKey
     * @param boolean $partial Defaults to false.
     */
    public function refreshFrom($values, $apiKey, $partial = false);
}
