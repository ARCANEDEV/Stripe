<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\RequestorInterface;
use Arcanedev\Stripe\Contracts\Utilities\Request\HttpClientInterface;
use Arcanedev\Stripe\Exceptions\ApiConnectionException;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\ApiKeyNotSetException;
use Arcanedev\Stripe\Exceptions\AuthenticationException;
use Arcanedev\Stripe\Exceptions\CardException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Exceptions\RateLimitException;
use Arcanedev\Stripe\Utilities\ErrorsHandler;
use Arcanedev\Stripe\Utilities\Request\HttpClient;

/**
 * Class Requestor
 * @package Arcanedev\Stripe
 */
class Requestor implements RequestorInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The API key that's to be used to make requests.
     *
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiBaseUrl;

    /**
     * @var HttpClientInterface
     */
    private static $httpClient;

    /**
     * @var array
     */
    private static $allowedMethods = [
        'get', 'post', 'delete'
    ];

    /**
     * @var ErrorsHandler
     */
    private $errorsHandler;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor
     *
     * @param string|null $apiKey
     * @param string|null $apiBase
     */
    public function __construct($apiKey = null, $apiBase = null)
    {
        $this->setApiKey($apiKey);
        $this->setApiBase($apiBase);
        $this->errorsHandler = new ErrorsHandler;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Stripe API Key
     *
     * @return string
     */
    public function getApiKey()
    {
        if ( ! $this->apiKey) {
            $this->setApiKey(Stripe::getApiKey());
        }

        return trim($this->apiKey);
    }

    /**
     * Set API Key
     *
     * @param  string $apiKey
     *
     * @return Requestor
     */
    private function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set API Base URL
     *
     * @param  string|null $apiBaseUrl
     *
     * @return Requestor
     */
    private function setApiBase($apiBaseUrl)
    {
        if (empty($apiBaseUrl)) {
            $apiBaseUrl = Stripe::getApiBaseUrl();
        }

        $this->apiBaseUrl = $apiBaseUrl;

        return $this;
    }

    /**
     * Get the HTTP client
     *
     * @return HttpClientInterface
     */
    private function httpClient()
    {
        if ( ! self::$httpClient) {
            self::$httpClient = HttpClient::instance();
        }

        return self::$httpClient;
    }

    /**
     * Set the HTTP client
     *
     * @param HttpClientInterface $client
     */
    public static function setHttpClient(HttpClientInterface $client)
    {
        self::$httpClient = $client;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Requestor with static method
     *
     * @param  string|null $apiKey
     * @param  string      $apiBase
     *
     * @return Requestor
     */
    public static function make($apiKey = null, $apiBase = '')
    {
        return new self($apiKey, $apiBase);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param  string     $url
     * @param  array|null $params
     * @param  array|null $headers
     *
     * @return array
     */
    public function get($url, $params = [], $headers = null)
    {
        return $this->request('get', $url, $params, $headers);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param  string     $url
     * @param  array|null $params
     * @param  array|null $headers
     *
     * @return array
     */
    public function post($url, $params = [], $headers = null)
    {
        return $this->request('post', $url, $params, $headers);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param  string     $url
     * @param  array|null $params
     * @param  array|null $headers
     *
     * @return array
     */
    public function delete($url, $params = [], $headers = null)
    {
        return $this->request('delete', $url, $params, $headers);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the request.
     *
     * @param  string     $method
     * @param  string     $url
     * @param  array|null $params
     * @param  array|null $headers
     *
     * @throws ApiException
     *
     * @return array
     */
    public function request($method, $url, $params = null, $headers = null)
    {
        $this->checkApiKey();
        $this->checkMethod($method);

        if (is_null($params)) {
            $params = [];
        }

        if (is_null($headers)) {
            $headers = [];
        }

        list($respBody, $respCode, $apiKey) = $this->requestRaw($method, $url, $params, $headers);

        $response = $this->interpretResponse($respBody, $respCode);

        return [$response, $apiKey];
    }

    /**
     * Interpret Response
     *
     * @param  string $respBody
     * @param  int    $respCode
     *
     * @throws ApiException
     * @throws AuthenticationException
     * @throws CardException
     * @throws InvalidRequestException
     * @throws RateLimitException
     *
     * @return array
     */
    private function interpretResponse($respBody, $respCode)
    {
        try {
            $response = json_decode($respBody, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception;
            }
        }
        catch (\Exception $e) {
            throw new ApiException(
                'Invalid response body from API: ' . $respBody . ' (HTTP response code was ' . $respCode .')',
                500,
                'api_error',
                null,
                $respBody
            );
        }

        $this->errorsHandler->handle($respBody, $respCode, $response);

        return $response;
    }

    /**
     * Raw request
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $params
     * @param  array  $headers
     *
     * @throws ApiConnectionException
     * @throws ApiException
     * @throws ApiKeyNotSetException
     *
     * @return array
     */
    private function requestRaw($method, $url, $params, $headers)
    {
        $absUrl = $this->apiBaseUrl . $url;
        $params = self::encodeObjects($params);

        list($respBody, $respCode) = $this->httpClient()
            ->setApiKey($this->getApiKey())
            ->request($method, $absUrl, $params, $headers);

        return [$respBody, $respCode, $this->getApiKey()];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the API Key is set
     *
     * @return bool
     */
    private function isApiKeyExists()
    {
        $apiKey = $this->getApiKey();

        return ! empty($apiKey);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Encode Objects
     *
     * @param  StripeResource|bool|array|string $obj
     *
     * @throws ApiException
     *
     * @return array|string
     */
    private static function encodeObjects($obj)
    {
        if ($obj instanceof StripeResource) {
            return str_utf8($obj->id);
        }

        if (is_bool($obj)) {
            return ($obj) ? 'true' : 'false';
        }

        if (is_array($obj)) {
            return array_map(function($v) {
                return self::encodeObjects($v);
            }, $obj);
        }

        return str_utf8($obj);
    }

    /**
     * Check if API Key Exists
     *
     * @throws ApiKeyNotSetException
     */
    private function checkApiKey()
    {
        if ( ! $this->isApiKeyExists()) {
            throw new ApiKeyNotSetException(
                'The Stripe API Key is required !'
            );
        }
    }

    /**
     * Check Http Method
     *
     * @param  string $method
     *
     * @throws ApiException
     */
    private function checkMethod(&$method)
    {
        $method = strtolower($method);

        if ( ! in_array($method, self::$allowedMethods)) {
            throw new ApiException(
                "Unrecognized method $method, must be [" . implode(', ', self::$allowedMethods) . '].',
                500
            );
        }
    }
}
