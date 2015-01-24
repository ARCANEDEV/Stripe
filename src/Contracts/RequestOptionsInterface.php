<?php namespace Arcanedev\Stripe\Contracts;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\RequestOptions;

interface RequestOptionsInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get API Key
     *
     * @return string
     */
    public function getApiKey();

    /**
     * Set API Key
     *
     * @param  string $apiKey
     *
     * @return RequestOptions
     */
    public function setApiKey($apiKey);

    /**
     * Get Headers
     *
     * @return array
     */
    public function getHeaders();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Unpacks an options array into an Options object
     *
     * @param  array|string $options
     *
     * @throws ApiException
     *
     * @return RequestOptions
     */
    public static function parse($options);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if API exists
     *
     * @return bool
     */
    public function hasApiKey();
}
