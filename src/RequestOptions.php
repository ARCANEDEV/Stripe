<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\RequestOptionsInterface;
use Arcanedev\Stripe\Exceptions\ApiException;

class RequestOptions implements RequestOptionsInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    public $apiKey;

    /** @var array */
    public $headers;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($apiKey, $headers)
    {
        $this->setApiKey($apiKey);
        $this->setHeaders($headers);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get API Key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set API Key
     *
     * @param string $apiKey
     *
     * @return RequestOptions
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey  = $apiKey;

        return $this;
    }

    /**
     * Get Headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set Headers
     *
     * @param array $headers
     *
     * @return $this
     */
    protected function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Unpacks an options array into an Options object
     *
     * @param array|string $options
     *
     * @throws ApiException
     *
     * @return RequestOptions
     */
    public static function parse($options)
    {
        if (! is_null($options) and ! is_string($options) and ! is_array($options)) {
            throw new ApiException("options must be a string, an array, or null");
        }

        if (is_null($options)) {
            return new self(null, []);
        }

        if (is_string($options)) {
            return new self($options, []);
        }

        // $options is array
        $key     = null;
        $headers = [];

        if (array_key_exists('api_key', $options)) {
            $key = $options['api_key'];
        }

        if (array_key_exists('idempotency_key', $options)) {
            $headers['Idempotency-Key'] = $options['idempotency_key'];
        }

        return new self($key, $headers);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if API exists
     *
     * @return bool
     */
    public function hasApiKey()
    {
        return ! is_null($this->apiKey);
    }
}
