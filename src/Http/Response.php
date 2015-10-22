<?php namespace Arcanedev\Stripe\Http;

use Arcanedev\Stripe\Contracts\Http\ResponseInterface;

/**
 * Class     Response
 *
 * @package  Arcanedev\Stripe\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Response implements ResponseInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $body;
    protected $code;
    protected $headers;
    protected $json;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Response instance.
     *
     * @param  string      $body
     * @param  int         $code
     * @param  array|null  $headers
     * @param  array|null  $json
     */
    public function __construct($body, $code, $headers, $json)
    {
        $this->body    = $body;
        $this->code    = $code;
        $this->headers = $headers;
        $this->json    = $json;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get response body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get response status code.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get response status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->getCode();
    }

    /**
     * Get response header.
     *
     * @return array|null
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get response json.
     *
     * @return array|null
     */
    public function getJson()
    {
        return $this->json;
    }
}
