<?php namespace Arcanedev\Stripe\Contracts\Utilities\Request;

use Arcanedev\Stripe\Exceptions\ApiConnectionException;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Utilities\Request\HttpClient;

interface HttpClientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set API Key
     *
     * @param  string $apiKey
     *
     * @return HttpClient
     */
    public function setApiKey($apiKey);

    /**
     * Set Base URL
     *
     * @param  string $apiBaseUrl
     *
     * @return HttpClient
     */
    public function setApiBaseUrl($apiBaseUrl);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Curl the request
     *
     * @param  string       $method
     * @param  string       $url
     * @param  array|string $params
     * @param  array        $headers
     *
     * @throws ApiConnectionException
     * @throws ApiException
     *
     * @return array
     */
    public function request($method, $url, $params, $headers);

}
