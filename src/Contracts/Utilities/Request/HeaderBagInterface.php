<?php namespace Arcanedev\Stripe\Contracts\Utilities\Request;

use Arcanedev\Stripe\Utilities\Request\HeaderBag;

interface HeaderBagInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Header Bag
     *
     * @param string $apiKey
     * @param array  $headers
     * @param bool   $hasFile
     *
     * @return array
     */
    public function make($apiKey, array $headers = [], $hasFile = false);

    /**
     * Prepare Headers
     *
     * @param string $apiKey
     * @param array  $headers
     * @param bool   $hasFile
     *
     * @return HeaderBag
     */
    public function prepare($apiKey, array $headers = [], $hasFile = false);

    /**
     * Get all headers
     *
     * @return array
     */
    public function get();

    /**
     * Get all headers
     *
     * @return array
     */
    public function toArray();

    /**
     * Return headers count
     *
     * @return int
     */
    public function count();
}
