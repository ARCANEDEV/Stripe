<?php namespace Arcanedev\Stripe\Contracts\Http;

/**
 * Interface  ResponseInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Http
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ResponseInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get response body.
     *
     * @return string
     */
    public function getBody();

    /**
     * Get response status code.
     *
     * @return int
     */
    public function getCode();

    /**
     * Get response status code.
     *
     * @return int
     */
    public function getStatusCode();

    /**
     * Get response header.
     *
     * @return array|null
     */
    public function getHeaders();

    /**
     * Get response json.
     *
     * @return array|null
     */
    public function getJson();
}
