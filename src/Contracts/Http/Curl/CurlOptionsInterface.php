<?php namespace Arcanedev\Stripe\Contracts\Http\Curl;

/**
 * Interface  CurlOptionsInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Http\Curl
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CurlOptionsInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set Options.
     *
     * @param  array  $options
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Add Option.
     *
     * @param  int    $option
     * @param  mixed  $value
     *
     * @return self
     */
    public function setOption($option, $value);

    /**
     * Get all options.
     *
     * @return array
     */
    public function get();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Curl Options.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  string  $params
     * @param  array   $headers
     * @param  bool    $hasFile
     *
     * @return self
     */
    public function make($method, $url, $params, $headers, $hasFile = false);
}
