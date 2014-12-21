<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\Exceptions\InvalidRequestException;

interface ResourceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the refreshed resource.
     *
     * @returns Resource
     */
    public function refresh();

    /**
     * Get The name of the class, with namespacing and underscores stripped.
     *
     * @param string $class
     *
     * @return string
     */
    public static function className($class = '');

    /**
     * Get the endpoint URL for the given class.
     *
     * @param string $class
     *
     * @return string
     */
    public static function classUrl($class = '');

    /**
     * @throws InvalidRequestException
     *
     * @return string The full API URL for this API resource.
     */
    public function instanceUrl();
}
