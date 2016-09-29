<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\StripeInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\ApiKeyNotSetException;

/**
 * Class     Stripe
 *
 * @package  Arcanedev\Stripe
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Stripe implements StripeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Library Version.
     *
     * @var string
     */
    const VERSION = '4.0.0';

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
     * The account ID for connected accounts requests.
     *
     * @var string|null
     */
    public static $accountId     = null;

    /**
     * Verify SSL Certs.
     *
     * @var bool
     */
    public static $verifySslCerts = true;

    /**
     * The application's information (name, version, URL).
     *
     * @var array
     */
    public static $appInfo = [];

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
     * @param  string  $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::checkApiKey($apiKey);

        self::$apiKey = $apiKey;
    }

    /**
     * Get API Base URL.
     *
     * @return string
     */
    public static function getApiBaseUrl()
    {
        return self::$apiBaseUrl;
    }

    /**
     * Set API Base URL.
     *
     * @param  string  $apiBaseUrl
     */
    public static function setApiBaseUrl($apiBaseUrl)
    {
        self::checkApiBaseUrl($apiBaseUrl);

        self::$apiBaseUrl = $apiBaseUrl;
    }

    /**
     * Get Upload Base URL.
     *
     * @return string
     */
    public static function getUploadBaseUrl()
    {
        return self::$uploadBaseUrl;
    }

    /**
     * Set Upload Base URL.
     *
     * @param  string  $uploadBaseUrl
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
     * @param  string  $apiVersion
     */
    public static function setApiVersion($apiVersion)
    {
        self::checkApiVersion($apiVersion);

        self::$apiVersion = $apiVersion;
    }

    /**
     * Get the Stripe account ID for connected accounts requests.
     *
     * @return string|null
     */
    public static function getAccountId()
    {
        return self::$accountId;
    }

    /**
     * Set the Stripe account ID to set for connected accounts requests.
     *
     * @param  string  $accountId
     */
    public static function setAccountId($accountId)
    {
        self::$accountId = $accountId;
    }

    /**
     * Get Verify SSL Certs.
     *
     * @return bool
     */
    public static function getVerifySslCerts()
    {
        return self::$verifySslCerts;
    }

    /**
     * Sets Verify SSL Certs.
     *
     * @param  bool  $verify
     */
    public static function setVerifySslCerts($verify)
    {
        self::$verifySslCerts = validate_bool($verify);
    }

    /**
     * Get the Application's information.
     *
     * @return array
     */
    public static function getAppInfo()
    {
        return self::$appInfo;
    }

    /**
     * Set the Application's information.
     *
     * @param  string  $name     The application's name
     * @param  string  $version  The application's version
     * @param  string  $url      The application's URL
     */
    public static function setAppInfo($name, $version = null, $url = null)
    {
        self::$appInfo = compact('name', 'version', 'url');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Init Stripe.
     *
     * @param  string  $apiKey
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
     * Check API Key.
     *
     * @param  string  $apiKey
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     * @throws \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     */
    private static function checkApiKey(&$apiKey)
    {
        if ( ! is_string($apiKey)) {
            throw new ApiException(
                'The API KEY must be a string value.',
                500
            );
        }

        $apiKey = trim($apiKey);

        if (empty($apiKey)) {
            throw new ApiKeyNotSetException(
                'You must specify your api key to use stripe.'
            );
        }
    }

    /**
     * Check API Base URL.
     *
     * @param  string  $apiBaseUrl
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkApiBaseUrl($apiBaseUrl)
    {
        if ( ! is_string($apiBaseUrl)) {
            throw new ApiException(
                'The API base URL be string value. ' . gettype($apiBaseUrl) . ' is given.',
                500
            );
        }

        if ( ! validate_url($apiBaseUrl)) {
            throw new ApiException(
                'The API base URL is not a valid URL. ',
                500
            );
        }
    }

    /**
     * Check Upload Base URL.
     *
     * @param  string  $uploadBaseUrl
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkUploadBaseUrl($uploadBaseUrl)
    {
        if ( ! is_string($uploadBaseUrl)) {
            throw new ApiException(
                'The Upload base URL be string value. ' . gettype($uploadBaseUrl) . ' is given.',
                500
            );
        }

        if ( ! validate_url($uploadBaseUrl)) {
            throw new ApiException(
                'The Upload base URL is not a valid URL. ',
                500
            );
        }
    }

    /**
     * Check API Version.
     *
     * @param  string|null  $apiVersion
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkApiVersion(&$apiVersion)
    {
        if (
            ! is_null($apiVersion) &&
            ! is_string($apiVersion)
        ) {
            throw new ApiException(
                'The API version must be a null or string value. ' . gettype($apiVersion) . ' is given.',
                500
            );
        }

        if (is_null($apiVersion)) {
            return;
        }

        $apiVersion = trim($apiVersion);

        if ( ! validate_version($apiVersion)) {
            throw new ApiException(
                'The API version must valid a semantic version [x.x.x].',
                500
            );
        }
    }

    /**
     * Check if API version exists.
     *
     * @return bool
     */
    public static function hasApiVersion()
    {
        return ! empty(self::$apiVersion);
    }

    /**
     * Check if the Stripe has account ID for connected accounts requests.
     *
     * @return bool
     */
    public static function hasAccountId()
    {
        return ! empty(self::$accountId);
    }
}
