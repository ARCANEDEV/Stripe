<?php namespace Arcanedev\Stripe\Contracts\Utilities;

/**
 * Interface HttpRequestInterface
 * @package Arcanedev\Stripe\Contracts\Utilities
 */
interface HttpRequestInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set URL
     *
     * @param string $url
     *
     * @return HttpRequestInterface
     */
    public function url($url);

    /**
     * Set URL
     *
     * @param string $url
     *
     * @return HttpRequestInterface
     */
    public function setUrl($url);

    /**
     * Set CURL option
     *
     * @param int   $name
     * @param mixed $value
     *
     * @return HttpRequestInterface
     */
    public function setOption($name, $value);

    /**
     * Set CURL options
     *
     * @param array $options
     *
     * @return HttpRequestInterface
     */
    public function setOptions(array $options);

    /**
     * @param $headers
     *
     * @return HttpRequestInterface
     */
    public function setHttpHeaders($headers);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the CURL handler
     *
     * @return mixed
     */
    public function execute();

    /**
     * Close curl handler
     */
    public function close();

    /* ------------------------------------------------------------------------------------------------
     |  SSL Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has SSL Errors
     *
     * @return bool
     */
    public function hasSSLErrors();

    /**
     * Enable CURL SSL Verify Peer
     *
     * @return HttpRequestInterface
     */
    public function enableSSLVerifyPeer();

    /**
     * Enable CURL SSL Verify Peer
     *
     * @return HttpRequestInterface
     */
    public function disableSSLVerifyPeer();

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has response
     *
     * @return bool
     */
    public function hasResponse();
}
