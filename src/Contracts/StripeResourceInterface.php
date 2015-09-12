<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\Exceptions\InvalidRequestException;

/**
 * Interface  StripeResourceInterface
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * @returns self
     */
    public function refresh();

    /**
     * Get The name of the class, with namespacing and underscores stripped.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function className($class = '');

    /**
     * Get the endpoint URL for the given class.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function classUrl($class = '');

    /**
     * Get the full API URL for this API resource.
     *
     * @throws InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl();
}
