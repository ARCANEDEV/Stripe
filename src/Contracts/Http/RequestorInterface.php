<?php namespace Arcanedev\Stripe\Contracts\Http;

/**
 * Interface  RequestorInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Http
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * Make requestor instance.
     *
     * @param  string|null  $apiKey
     *
     * @return self
     */
    public static function make($apiKey = null);

    /* ------------------------------------------------------------------------------------------------
     |  Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * GET request.
     *
     * @param  string      $url
     * @param  array|null  $params
     *
     * @return array
     */
    public function get($url, $params = null);

    /**
     * POST request.
     *
     * @param  string      $url
     * @param  array|null  $params
     *
     * @return array
     */
    public function post($url, $params = null);

    /**
     * DELETE request.
     *
     * @param  string      $url
     * @param  array|null  $params
     *
     * @return array
     */
    public function delete($url, $params = null);

    /**
     * Make a request.
     *
     * Note: An array whose first element is the response and second element is the API key used to make the request.
     *
     * @param  string      $method
     * @param  string      $url
     * @param  array|null  $params
     *
     * @return array
     */
    public function request($method, $url, $params = null);
}
