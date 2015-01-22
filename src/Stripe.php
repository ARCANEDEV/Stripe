<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\StripeInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\ApiKeyNotSetException;

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
    private static $apiKey;

    /**
     * The base URL for the Stripe API.
     *
     * @var string
     */
    public static $apiBaseUrl    = 'https://api.stripe.com';

    /**
     * The base URL for the Stripe API uploads endpoint.
     *
     * @var string
     */
    public static $uploadBaseUrl = 'https://uploads.stripe.com';

    /**
     * The version of the Stripe API to use for requests.
     *
     * @var string|null
     */
    public static $apiVersion    = null;

    /**
     * Verify SSL Certs
     *
     * @var boolean
     */
    public static $verifySslCerts = true;

    /**
     * Library Version
     *
     * @var string|null
     */
    const VERSION = '1.18.0';

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
     *
     * @throws ApiException
     * @throws ApiKeyNotSetException
     */
    public static function setApiKey($apiKey)
    {
        self::checkApiKey($apiKey);

        self::$apiKey = $apiKey;
    }

    /**
     * Get API Base URL
     *
     * @return string
     */
    public static function getApiBaseUrl()
    {
        return self::$apiBaseUrl;
    }

    /**
     * Set API Base URL
     *
     * @param string $apiBaseUrl
     *
     * @throws ApiException
     */
    public static function setApiBaseUrl($apiBaseUrl)
    {
        self::checkApiBaseUrl($apiBaseUrl);

        self::$apiBaseUrl = $apiBaseUrl;
    }

    /**
     * Get Upload Base URL
     *
     * @return string
     */
    public static function getUploadBaseUrl()
    {
        return self::$uploadBaseUrl;
    }


    /**
     * Set Upload Base URL
     *
     * @param string $uploadBaseUrl
     *
     * @throws ApiException
     */
    public static function setUploadBaseUrl($uploadBaseUrl)
    {
        self::checkUploadBaseUrl($uploadBaseUrl);

        self::$uploadBaseUrl = $uploadBaseUrl;
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
        self::checkApiVersion($apiVersion);

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

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check API Key
     *
     * @param string $apiKey
     *
     * @throws ApiException
     * @throws ApiKeyNotSetException
     */
    private static function checkApiKey(&$apiKey)
    {
        if (! is_string($apiKey)) {
            $msg = 'The API KEY must be a string value.';

            throw new ApiException($msg, 500);
        }

        $apiKey = trim($apiKey);

        if (empty($apiKey)) {
            $msg = 'You must specify your api key to use stripe.';

            throw new ApiKeyNotSetException($msg, 500);
        }
    }

    /**
     * Check API Base URL
     *
     * @param string $apiBaseUrl
     *
     * @throws ApiException
     */
    private static function checkApiBaseUrl($apiBaseUrl)
    {
        if (! is_string($apiBaseUrl)) {
            $msg = 'The API base URL be string value. ' . gettype($apiBaseUrl) . ' is given.';

            throw new ApiException($msg, 500);
        }

        if (! validate_url($apiBaseUrl)) {
            throw new ApiException('The API base URL is not a valid URL. ', 500);
        }
    }

    /**
     * Check Upload Base URL
     *
     * @param string $uploadBaseUrl
     *
     * @throws ApiException
     */
    private static function checkUploadBaseUrl($uploadBaseUrl)
    {
        if (! is_string($uploadBaseUrl)) {
            $msg = 'The Upload base URL be string value. ' . gettype($uploadBaseUrl) . ' is given.';

            throw new ApiException($msg, 500);
        }

        if (! validate_url($uploadBaseUrl)) {
            throw new ApiException('The Upload base URL is not a valid URL. ', 500);
        }
    }

    /**
     * Check API Version
     *
     * @param string|null $apiVersion
     *
     * @throws ApiException
     */
    private static function checkApiVersion(&$apiVersion)
    {
        if (! is_null($apiVersion) and ! is_string($apiVersion)) {
            $msg = 'The API version must be a null or string value. ' . gettype($apiVersion) . ' is given.';

            throw new ApiException($msg, 500);
        }

        if (is_null($apiVersion)) {
            return;
        }

        $apiVersion = trim($apiVersion);

        if (! validate_version($apiVersion)) {
            $msg = 'The API version must valid a semantic version [x.x.x].';

            throw new ApiException($msg, 500);
        }
    }

    /**
     * Check if API version exists
     *
     * @return bool
     */
    public static function hasApiVersion()
    {
        return ! empty(self::$apiVersion);
    }
}
