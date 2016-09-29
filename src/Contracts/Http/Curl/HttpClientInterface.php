<?php namespace Arcanedev\Stripe\Contracts\Http\Curl;

/**
 * Interface  HttpClientInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Http\Curl
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface HttpClientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set API Key.
     *
     * @param  string  $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey);

    /**
     * Set Base URL
     *
     * @param  string  $apiBaseUrl
     *
     * @return self
     */
    public function setApiBaseUrl($apiBaseUrl);

    /**
     * Get the timeout.
     *
     * @return int
     */
    public function getTimeout();

    /**
     * Set the timeout.
     *
     * @param  int  $seconds
     *
     * @return self
     */
    public function setTimeout($seconds);

    /**
     * Get the connect timeout.
     *
     * @return int
     */
    public function getConnectTimeout();

    /**
     * Set the connect timeout.
     *
     * @param  int  $seconds
     *
     * @return self
     */
    public function setConnectTimeout($seconds);

    /**
     * Get array options.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Set array options.
     *
     * @param  array  $options
     *
     * @return self
     */
    public function setOptionArray(array $options);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make the HTTP Client with options.
     *
     * @param  array  $options
     *
     * @return static
     */
    public static function make(array $options = []);

    /**
     * Curl the request.
     *
     * @param  string        $method
     * @param  string        $url
     * @param  array|string  $params
     * @param  array         $headers
     * @param  bool          $hasFile
     *
     * @return array
     */
    public function request($method, $url, $params, $headers, $hasFile);
}
