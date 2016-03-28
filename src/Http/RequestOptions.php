<?php namespace Arcanedev\Stripe\Http;

use Arcanedev\Stripe\Contracts\Http\RequestOptionsInterface;
use Arcanedev\Stripe\Exceptions\ApiException;

/**
 * Class     RequestOptions
 *
 * @package  Arcanedev\Stripe\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
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
     * Create a RequestOptions instance.
     *
     * @param  string|null  $apiKey
     * @param  array        $headers
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
     * Get API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set API Key.
     *
     * @param  string  $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        if ( ! is_null($apiKey)) {
            $this->apiKey = trim($apiKey);
        }

        return $this;
    }

    /**
     * Get Headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set Headers.
     *
     * @param  array  $headers
     *
     * @return self
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
     * Unpacks an options array and merges it into the existing RequestOptions object.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function merge($options)
    {
        $otherOptions = self::parse($options);

        if (is_null($otherOptions->apiKey))
            $otherOptions->apiKey = $this->apiKey;

        $otherOptions->headers = array_merge($this->headers, $otherOptions->headers);

        return $otherOptions;
    }

    /**
     * Unpacks an options array into an Options object.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function parse($options)
    {
        self::checkOptions($options);

        if ($options instanceof self) return $options;

        if (is_null($options) || is_string($options)) {
            return new self($options, []);
        }

        // $options is array
        $key     = null;
        if (isset($options['api_key'])) {
            $key = $options['api_key'];
        }

        $headers = self::prepareHeaders($options);

        return new self($key, $headers);
    }

    /**
     * Prepare headers.
     *
     * @param  array  $options
     *
     * @return array
     */
    private static function prepareHeaders($options = [])
    {
        $headers = [];
        $keys    = [
            'idempotency_key' => 'Idempotency-Key',
            'stripe_account'  => 'Stripe-Account',
            'stripe_version'  => 'Stripe-Version',
        ];

        foreach ($keys as $keyFrom => $keyTo) {
            if (isset($options[$keyFrom]))
                $headers[$keyTo] = $options[$keyFrom];
        }

        return $headers;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if API exists.
     *
     * @return bool
     */
    public function hasApiKey()
    {
        return ! is_null($this->apiKey) && ! empty($this->apiKey);
    }

    /**
     * Check Options.
     *
     * @param  array|string|null  $options
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkOptions($options)
    {
        if (
            ! ($options instanceof self) &&
            ! is_null($options) &&
            ! is_string($options) &&
            ! is_array($options)
        ) {
            throw new ApiException(
                'Options must be a string, an array, or null, ' . gettype($options) . ' given.'
            );
        }
    }
}
