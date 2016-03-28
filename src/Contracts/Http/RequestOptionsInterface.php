<?php namespace Arcanedev\Stripe\Contracts\Http;

/**
 * Interface  RequestOptionsInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Http
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RequestOptionsInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get API Key.
     *
     * @return string
     */
    public function getApiKey();

    /**
     * Set API Key.
     *
     * @param  string  $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey);

    /**
     * Get Headers.
     *
     * @return array
     */
    public function getHeaders();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Unpacks an options array into an Options object.
     *
     * @param  array|string  $options
     *
     * @return self
     */
    public static function parse($options);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if API exists.
     *
     * @return bool
     */
    public function hasApiKey();
}
