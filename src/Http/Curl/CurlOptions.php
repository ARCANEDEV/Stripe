<?php namespace Arcanedev\Stripe\Http\Curl;

use Arcanedev\Stripe\Contracts\Http\Curl\CurlOptionsInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\BadMethodCallException;
use Arcanedev\Stripe\Exceptions\InvalidArgumentException;
use Arcanedev\Stripe\Stripe;

/**
 * Class     CurlOptions
 *
 * @package  Arcanedev\Stripe\Http\Curl
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurlOptions implements CurlOptionsInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    protected $options = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create the CurlOptions instance.
     */
    public function __construct()
    {
        $this->options = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set Options.
     *
     * @param  array  $options
     *
     * @return self
     */
    public function setOptions(array $options)
    {
        foreach($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }

    /**
     * Add Option.
     *
     * @param  int    $option
     * @param  mixed  $value
     *
     * @return self
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Curl Options.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  string  $params
     * @param  array   $headers
     * @param  bool    $hasFile
     *
     * @return self
     */
    public function make($method, $url, $params, $headers, $hasFile = false)
    {
        $this->checkMethod($method);

        $this->options = [];

        $this->prepareMethodOptions($method, $params, $hasFile);
        $this->setOptions([
            CURLOPT_URL            => str_utf8($url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 80,
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        if ( ! Stripe::$verifySslCerts) {
            $this->setOption(CURLOPT_SSL_VERIFYPEER, false);
        }

        return $this;
    }

    /**
     * Prepare options based on METHOD.
     *
     * @param  string  $method
     * @param  string  $params
     * @param  bool    $hasFile
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private function prepareMethodOptions($method, $params, $hasFile)
    {
        if ($method === 'GET' && $hasFile) {
            throw new ApiException(
                'Issuing a GET request with a file parameter'
            );
        }

        $options = [
            'GET'  => [
                CURLOPT_HTTPGET       => true,
                CURLOPT_CUSTOMREQUEST => 'GET'
            ],
            'POST' => [
                CURLOPT_POST          => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS    => $params
            ],
            'DELETE' => [
                CURLOPT_CUSTOMREQUEST => 'DELETE'
            ]
        ];

        $this->setOptions($options[$method]);
    }

    /**
     * Get all options.
     *
     * @return array
     */
    public function get()
    {
        return $this->options;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check Method.
     *
     * @param  string  $method
     *
     * @throws \Arcanedev\Stripe\Exceptions\BadMethodCallException
     * @throws \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    private function checkMethod(&$method)
    {
        if (! is_string($method)) {
            throw new InvalidArgumentException(
                'The method must be a string value, ' . gettype($method) . ' given',
                500
            );
        }

        $method = strtoupper(trim($method));

        if (! in_array($method, ['GET', 'POST', 'DELETE'])) {
            throw new BadMethodCallException(
                'The method [' . $method . '] is not allowed',
                405
            );
        }
    }
}
