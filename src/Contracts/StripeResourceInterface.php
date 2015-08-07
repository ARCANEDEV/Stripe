<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\StripeResource;

/**
 * Interface StripeResourceInterface
 * @package Arcanedev\Stripe\Contracts
 */
interface StripeResourceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the refreshed resource.
     *
     * @returns StripeResource
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
