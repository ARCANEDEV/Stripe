<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\StripeInterface;

abstract class Stripe implements StripeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The Stripe API key to be used for requests.
     *
     * @var string
     */
    public static $apiKey;

    /**
     * The base URL for the Stripe API.
     *
     * @var string
     */
    public static $apiBase        = 'https://api.stripe.com';

    /**
     * The base URL for the Stripe API uploads endpoint.
     *
     * @var string
     */
    public static $apiUploadBase  = 'https://uploads.stripe.com';

    /**
     * The version of the Stripe API to use for requests.
     *
     * @var string|null
     */
    public static $apiVersion = null;

    /**
     * Verify SSL Certs (Default: True)
     *
     * @var boolean
     */
    public static $verifySslCerts = true;

    /**
     * Library Version
     *
     * @var string|null
     */
    const VERSION = '1.17.4';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the API key used for requests.
     *
     * @return string
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * The API version used for requests. null if we're using the latest version.
     *
     * @return string
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * The API version used for requests. null if we're using the latest version.
     *
     * @return string
     */
    public static function version()
    {
        return self::getApiVersion();
    }

    /**
     * Sets the API version to use for requests.
     *
     * @param string $apiVersion
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * Get Verify SSL Certs
     *
     * @return bool
     */
    public static function getVerifySslCerts()
    {
        return self::$verifySslCerts;
    }

    /**
     * Sets Verify SSL Certs
     *
     * @param bool $verify
     */
    public static function setVerifySslCerts($verify)
    {
        self::$verifySslCerts = validate_bool($verify);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Init Stripe
     *
     * @param string $apiKey
     */
    public static function init($apiKey)
    {
        self::setApiKey($apiKey);
    }
}
