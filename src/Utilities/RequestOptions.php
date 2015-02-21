<?php namespace Arcanedev\Stripe\Utilities;

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
    /**
     * @param string|null $apiKey
     * @param array       $headers
     */
    public function __construct($apiKey = null, array $headers = [])
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
     * @param  string $apiKey
     *
     * @return RequestOptions
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey  = trim($apiKey);

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
     * @param  array $headers
     *
     * @return RequestOptions
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
     * Unpacks an options array and merges it into the existing RequestOptions
     * object.
     * @param array|string|null $options a key => value array
     *
     * @return RequestOptions
     */
    public function merge($options)
    {
        $otherOptions = self::parse($options);

        if ($otherOptions->apiKey === null) {
            $otherOptions->apiKey = $this->apiKey;
        }

        $otherOptions->headers = array_merge($this->headers, $otherOptions->headers);

        return $otherOptions;
    }

    /**
     * Unpacks an options array into an Options object
     *
     * @param  array|string|null $options
     *
     * @throws ApiException
     *
     * @return RequestOptions
     */
    public static function parse($options)
    {
        self::checkOptions($options);

        if ($options instanceof self) {
            return $options;
        }

        if (is_null($options)) {
            return new self(null, []);
        }

        if (is_string($options)) {
            return new self($options, []);
        }

        // $options is array
        $key     = null;
        if (array_key_exists('api_key', $options)) {
            $key = $options['api_key'];
        }

        $headers = self::prepareHeaders($options);

        return new self($key, $headers);
    }

    private static function prepareHeaders($options = [])
    {
        $headers = [];

        if (array_key_exists('idempotency_key', $options)) {
            $headers['Idempotency-Key'] = $options['idempotency_key'];
        }

        if (array_key_exists('stripe_account', $options)) {
            $headers['Stripe-Account'] = $options['stripe_account'];
        }

        if (array_key_exists('stripe_version', $options)) {
            $headers['Stripe-Version'] = $options['stripe_version'];
        }

        return $headers;
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
        return ! is_null($this->apiKey) and ! empty($this->apiKey);
    }

    /**
     * Check Options
     *
     * @param  array|string|null $options
     *
     * @throws ApiException
     */
    private static function checkOptions($options)
    {
        if (
            ! ($options instanceof self) and
            ! is_null($options) and
            ! is_string($options) and
            ! is_array($options)
        ) {
            throw new ApiException(
                'Options must be a string, an array, or null, ' . gettype($options) . ' given.'
            );
        }
    }
}
