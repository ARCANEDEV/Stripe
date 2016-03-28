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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
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
