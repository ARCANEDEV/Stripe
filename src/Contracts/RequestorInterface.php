<?php namespace Arcanedev\Stripe\Contracts;

/**
 * Interface RequestorInterface
 * @package Arcanedev\Stripe\Contracts
 */
interface RequestorInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Stripe API Key
     *
     * @return string|null
     */
    public function getApiKey();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string|null $apiKey
     *
     * @return RequestorInterface
     */
    public static function make($apiKey = null);

    /* ------------------------------------------------------------------------------------------------
     |  Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function get($url, $params = null);

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function post($url, $params = null);

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function delete($url, $params = null);

    /**
     * An array whose first element is the response and second element is the API key used to make the request.
     *
     * @param string $method
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function request($method, $url, $params = null);

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
}
