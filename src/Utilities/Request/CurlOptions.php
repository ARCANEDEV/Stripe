<?php namespace Arcanedev\Stripe\Utilities\Request;

use Arcanedev\Stripe\Contracts\Utilities\Request\CurlOptionsInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\BadMethodCallException;
use Arcanedev\Stripe\Exceptions\InvalidArgumentException;
use Arcanedev\Stripe\Stripe;

class CurlOptions implements CurlOptionsInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $options = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->options = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set Options
     *
     * @param array $options
     *
     * @return CurlOptions
     */
    public function setOptions(array $options)
    {
        foreach($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }

    /**
     * Add Option
     *
     * @param  int   $option
     * @param  mixed $value
     *
     * @return CurlOptions
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
     * Make Curl Options
     *
     * @param  string $method
     * @param  string $absUrl
     * @param  string $params
     * @param  array  $headers
     * @param  bool   $hasFile
     *
     * @throws ApiException
     *
     * @return CurlOptions
     */
    public function make($method, $absUrl, $params, $headers, $hasFile = false)
    {
        $this->checkMethod($method);

        $this->init();

        $this->prepareMethodOptions($method, $params, $hasFile);

        $this->setOptions([
            CURLOPT_URL            => str_utf8($absUrl),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 80,
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        if (! Stripe::$verifySslCerts) {
            $this->setOption(CURLOPT_SSL_VERIFYPEER, false);
        }

        return $this;
    }

    /**
     * Prepare options based on METHOD
     *
     * @param  string $method
     * @param  string $params
     * @param  bool $hasFile
     *
     * @throws ApiException
     */
    private function prepareMethodOptions($method, $params, $hasFile)
    {
        switch ($method) {
            case 'POST':
                $this->setOptions([
                    CURLOPT_POST          => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS    => $params
                ]);
                break;

            case 'DELETE':
                $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            case 'GET':
            default:
                if ($hasFile) {
                    throw new ApiException(
                        'Issuing a GET request with a file parameter'
                    );
                }
                $this->setOptions([
                    CURLOPT_HTTPGET       => true,
                    CURLOPT_CUSTOMREQUEST => 'GET'
                ]);
        }
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function get()
    {
        return $this->options;
    }

    /**
     * Check Method
     *
     * @param string $method
     *
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
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
