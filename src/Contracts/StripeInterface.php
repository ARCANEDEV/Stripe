<?php namespace Arcanedev\Stripe\Contracts;

interface StripeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey();

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey);

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion();

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function version();

    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion);

    /**
     * @return boolean
     */
    public static function getVerifySslCerts();

    /**
     * @param boolean $verify
     */
    public static function setVerifySslCerts($verify);
}
