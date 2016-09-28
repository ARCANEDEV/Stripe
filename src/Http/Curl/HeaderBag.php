<?php namespace Arcanedev\Stripe\Http\Curl;

use Arcanedev\Stripe\Contracts\Http\Curl\HeaderBagInterface;
use Arcanedev\Stripe\Stripe;

/**
 * Class     HeaderBag
 *
 * @package  Arcanedev\Stripe\Http\Curl
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
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
    /**
     * Create the HeaderBag instance.
     */
    public function __construct()
    {
        $this->headers = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get default headers.
     *
     * @param  string  $apiKey
     * @param  bool    $hasFile
     *
     * @return array
     */
    private function getDefaults($apiKey, $hasFile = false)
    {
        $uaString = 'Stripe/v1 PhpBindings/' . Stripe::VERSION;
        $ua       = self::getUserAgent();
        $appInfo  = Stripe::getAppInfo();

        if ( ! empty($appInfo)) {
            $uaString         .= ' ' . self::formatAppInfo($appInfo);
            $ua['application'] = $appInfo;
        }

        $defaults = [
            'X-Stripe-Client-User-Agent' => json_encode($ua),
            'User-Agent'                 => $uaString,
            'Authorization'              => 'Bearer ' . $apiKey,
            'Content-Type'               => $hasFile ? 'multipart/form-data' : 'application/x-www-form-urlencoded',
            'Expect'                     => null,
        ];

        if (Stripe::hasApiVersion())
            $defaults['Stripe-Version'] = Stripe::getApiVersion();

        if (Stripe::hasAccountId())
            $defaults['Stripe-Account'] = Stripe::getAccountId();

        return $defaults;
    }

    /**
     * Get User Agent.
     *
     * @return array
     */
    private static function getUserAgent()
    {
        $curlVersion = curl_version();

        return [
            'bindings_version' => Stripe::VERSION,
            'lang'             => 'php',
            'lang_version'     => phpversion(),
            'publisher'        => 'stripe',
            'uname'            => php_uname(),
            'httplib'          => 'curl ' . $curlVersion['version'],
            'ssllib'           => $curlVersion['ssl_version'],
        ];
    }

    /**
     * Format the Application's information.
     *
     * @param  array  $appInfo
     *
     * @return string|null
     */
    private static function formatAppInfo(array $appInfo)
    {
        $string = $appInfo['name'];

        if ($appInfo['version'] !== null) {
            $string .= '/' . $appInfo['version'];
        }
        if ($appInfo['url'] !== null) {
            $string .= ' (' . $appInfo['url'] . ')';
        }

        return $string;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Header Bag.
     *
     * @param  string  $apiKey
     * @param  array   $headers
     * @param  bool    $hasFile
     *
     * @return array
     */
    public function make($apiKey, array $headers = [], $hasFile = false)
    {
        return $this->prepare($apiKey, $headers, $hasFile)->get();
    }

    /**
     * Prepare Headers.
     *
     * @param  string  $apiKey
     * @param  array   $headers
     * @param  bool    $hasFile
     *
     * @return self
     */
    public function prepare($apiKey, array $headers = [], $hasFile = false)
    {
        $this->headers = [];

        $this->headers = array_merge(
            self::getDefaults($apiKey, $hasFile),
            $headers
        );

        return $this;
    }

    /**
     * Get all the headers.
     *
     * @return array
     */
    public function all()
    {
        return $this->headers;
    }

    /**
     * Get all headers.
     *
     * @return array
     */
    public function get()
    {
        $headers = $this->all();

        array_walk($headers, function(&$value, $header) {
            $value = "$header: $value";
        });

        return array_values($headers);
    }

    /**
     * Add a Header to collection.
     *
     * @param  string  $name
     * @param  string  $value
     *
     * @return self
     */
    public function set($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Get all headers.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * Return headers count.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }
}
