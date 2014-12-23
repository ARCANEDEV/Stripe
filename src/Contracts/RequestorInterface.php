<?php namespace Arcanedev\Stripe\Contracts;

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
    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @returns string|mixed The UTF8-encoded string, or the object passed in if it wasn't a string.
     */
    public static function utf8($value);

    /**
     *  A query string, essentially.
     *
     * @param array $arr An map of param keys to values.
     * @param string|null $prefix (It doesn't look like we ever use $prefix...)
     *
     * @returns string
     */
    public static function encode($arr, $prefix = null);
}
