<?php namespace Arcanedev\Stripe\Utilities\Request;

use Arcanedev\Stripe\Contracts\Utilities\Request\HeaderBagInterface;
use Arcanedev\Stripe\Stripe;

class HeaderBag implements HeaderBagInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    protected $headers = [];

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
        $this->headers = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get default headers
     *
     * @param  string $apiKey
     * @param  bool $hasFile
     *
     * @return array
     */
    private function getDefaults($apiKey, $hasFile)
    {
        $defaults = [
            'X-Stripe-Client-User-Agent' => self::getUserAgent(),
            'User-Agent'                 => 'Stripe/v1 PhpBindings/' . Stripe::VERSION,
            'Authorization'              => 'Bearer ' . $apiKey,
            'Content-Type'               => $hasFile
                ? 'multipart/form-data'
                : 'application/x-www-form-urlencoded',
        ];

        if (Stripe::hasApiVersion()) {
            $defaults['Stripe-Version'] = Stripe::getApiVersion();
        }

        return $defaults;
    }

    /**
     * Get User Agent (JSON format)
     *
     * @return string
     */
    private static function getUserAgent()
    {
        return json_encode([
            'bindings_version' => Stripe::VERSION,
            'lang'             => 'php',
            'lang_version'     => phpversion(),
            'publisher'        => 'stripe',
            'uname'            => php_uname(),
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Header Bag
     *
     * @param string  $apiKey
     * @param array  $headers
     * @param bool   $hasFile
     *
     * @return array
     */
    public function make($apiKey, array $headers = [], $hasFile = false)
    {
        return $this->prepare($apiKey, $headers, $hasFile)->get();
    }

    /**
     * Prepare Headers
     *
     * @param string $apiKey
     * @param array  $headers
     * @param bool   $hasFile
     *
     * @return HeaderBag
     */
    public function prepare($apiKey, array $headers = [], $hasFile = false)
    {
        $this->init();

        $this->headers = array_merge(
            self::getDefaults($apiKey, $hasFile),
            $headers
        );

        return $this;
    }

    /**
     * Get all headers
     *
     * @return array
     */
    public function get()
    {
        $headers = $this->headers;

        array_walk($headers, function(&$value, $header) {
            $value = $this->format($header, $value);
        });

        return array_values($headers);
    }

    /**
     * Add a Header to collection
     *
     * @param  string $name
     * @param  string $value
     *
     * @return HeaderBag
     */
    public function set($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Get all headers
     *
     * @return array
     */
    public function toArray()
    {
        return $this->headers;
    }

    /**
     * Return headers count
     *
     * @return int
     */
    public function count()
    {
        return count($this->headers);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Format header
     *
     * @param  string $header
     * @param  string $value
     *
     * @return string
     */
    private function format($header, $value)
    {
        return $header . ': ' . $value;
    }
}
