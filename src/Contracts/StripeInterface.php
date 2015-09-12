<?php namespace Arcanedev\Stripe\Contracts;

/**
 * Interface  StripeInterface
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface StripeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the API key used for requests.
     *
     * @return string
     */
    public static function getApiKey();

    /**
     * Sets the API key to be used for requests.
     *
     * @param  string  $apiKey
     */
    public static function setApiKey($apiKey);

    /**
     * The API version used for requests. null if we're using the latest version.
     *
     * @return string
     */
    public static function getApiVersion();

    /**
     * The API version used for requests. null if we're using the latest version.
     *
     * @return string
     */
    public static function version();

    /**
     * Sets the API version to use for requests.
     *
     * @param  string  $apiVersion
     */
    public static function setApiVersion($apiVersion);

    /**
     * Get Verify SSL Certs.
     *
     * @return bool
     */
    public static function getVerifySslCerts();

    /**
     * Sets Verify SSL Certs.
     *
     * @param  bool  $verify
     */
    public static function setVerifySslCerts($verify);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Init Stripe.
     *
     * @param  string  $apiKey
     */
    public static function init($apiKey);
}
