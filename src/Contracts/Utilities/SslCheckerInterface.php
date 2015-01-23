<?php namespace Arcanedev\Stripe\Contracts\Utilities;

use Arcanedev\Stripe\Exceptions\ApiConnectionException;
use Arcanedev\Stripe\Utilities\SslChecker;

interface SslCheckerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set URL
     *
     * @param string $url
     *
     * @return SslChecker
     */
    public function setUrl($url);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Preflight the SSL certificate presented by the backend. This isn't 100%
     * bulletproof, in that we're not actually validating the transport used to
     * communicate with Stripe, merely that the first attempt to does not use a
     * revoked certificate.
     *
     * Unfortunately the interface to OpenSSL doesn't make it easy to check the
     * certificate before sending potentially sensitive data on the wire. This
     * approach raises the bar for an attacker significantly.
     *
     * @param string $url
     *
     * @throws ApiConnectionException
     *
     * @return bool
     */
    public function checkCert($url);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check black list
     *
     * @param string $pemCert
     *
     * @throws ApiConnectionException
     */
    public function checkBlackList($pemCert);

    /**
     * Checks if a valid PEM encoded certificate is blacklisted
     *
     * @param string $cert
     *
     * @return bool
     */
    public function isBlackListed($cert);

    /**
     * Get the certificates file path
     *
     * @return string
     */
    public function caBundle();
}
