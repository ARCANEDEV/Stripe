<?php namespace Arcanedev\Stripe\Contracts;

interface ObjectInterface
{
    /**
     * Init the object
     */
    public static function init();


    /**
     * @return array
     */
    public function keys();

    /**
     * @param array $values
     * @param string|null $apiKey
     *
     * @return Object The object of the same class as $this constructed
     *    from the given values.
     */
    public static function constructFrom($values, $apiKey = null);

    /**
     * Refreshes this object using the provided values.
     *
     * @param array   $values
     * @param string  $apiKey
     * @param boolean $partial Defaults to false.
     */
    public function refreshFrom($values, $apiKey, $partial = false);
}
