<?php
//
namespace Arcanedev\Stripe\Contracts\Utilities\Request;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Utilities\Request\CurlOptions;

interface CurlOptionsInterface
{
    /**
     * Set Options
     *
     * @param array $options
     *
     * @return CurlOptions
     */
    public function setOptions(array $options);

    /**
     * Add Option
     *
     * @param  int   $option
     * @param  mixed $value
     *
     * @return CurlOptions
     */
    public function setOption($option, $value);

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
    public function make($method, $absUrl, $params, $headers, $hasFile = false);

    /**
     * Get all options
     *
     * @return array
     */
    public function get();
}