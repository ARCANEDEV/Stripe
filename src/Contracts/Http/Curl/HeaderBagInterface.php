<?php namespace Arcanedev\Stripe\Contracts\Http\Curl;

/**
 * Interface  HeaderBagInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Http\Curl
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface HeaderBagInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Header Bag.
     *
     * @param  string  $apiKey
     * @param  array   $headers
     * @param  bool    $hasFile
     *
     * @return array
     */
    public function make($apiKey, array $headers = [], $hasFile = false);

    /**
     * Prepare Headers.
     *
     * @param  string  $apiKey
     * @param  array   $headers
     * @param  bool    $hasFile
     *
     * @return self
     */
    public function prepare($apiKey, array $headers = [], $hasFile = false);

    /**
     * Get all the headers.
     *
     * @return array
     */
    public function all();

    /**
     * Get all headers.
     *
     * @return array
     */
    public function get();

    /**
     * Get all headers.
     *
     * @return array
     */
    public function toArray();

    /**
     * Return headers count.
     *
     * @return int
     */
    public function count();
}
