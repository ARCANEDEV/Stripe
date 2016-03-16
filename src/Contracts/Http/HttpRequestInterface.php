<?php namespace Arcanedev\Stripe\Contracts\Http;

/**
 * Interface  HttpRequestInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Http
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface HttpRequestInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set URL.
     *
     * @param  string  $url
     *
     * @return self
     */
    public function url($url);

    /**
     * Set URL.
     *
     * @param  string  $url
     *
     * @return self
     */
    public function setUrl($url);

    /**
     * Set CURL option.
     *
     * @param  int    $name
     * @param  mixed  $value
     *
     * @return self
     */
    public function setOption($name, $value);

    /**
     * Set CURL options.
     *
     * @param  array  $options
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Set HTTP Headers.
     *
     * @param  $headers
     *
     * @return self
     */
    public function setHttpHeaders($headers);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the CURL handler.
     *
     * @return mixed
     */
    public function execute();

    /**
     * Close curl handler.
     */
    public function close();

    /* ------------------------------------------------------------------------------------------------
     |  SSL Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has SSL Errors.
     *
     * @return bool
     */
    public function hasSSLErrors();

    /**
     * Enable CURL SSL Verify Peer.
     *
     * @return self
     */
    public function enableSSLVerifyPeer();

    /**
     * Enable CURL SSL Verify Peer.
     *
     * @return self
     */
    public function disableSSLVerifyPeer();

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has response.
     *
     * @return bool
     */
    public function hasResponse();
}
