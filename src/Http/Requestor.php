<?php namespace Arcanedev\Stripe\Http;

use Arcanedev\Stripe\Contracts\Http\Curl\HttpClientInterface;
use Arcanedev\Stripe\Contracts\Http\RequestorInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\ApiKeyNotSetException;
use Arcanedev\Stripe\Http\Curl\HttpClient;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\StripeResource;
use Arcanedev\Stripe\Utilities\ErrorsHandler;
use CURLFile;
use Exception;

/**
 * Class     Requestor
 *
 * @package  Arcanedev\Stripe\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * The API base URL.
     *
     * @var string
     */
    private $apiBaseUrl;

    /**
     * @var \Arcanedev\Stripe\Contracts\Http\Curl\HttpClientInterface
     */
    private static $httpClient;

    /**
     * The allowed HTTP methods.
     *
     * @var array
     */
    private static $allowedMethods = ['get', 'post', 'delete'];

    /**
     * @var \Arcanedev\Stripe\Utilities\ErrorsHandler
     */
    private $errorsHandler;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Requestor instance.
     *
     * @param  string|null  $apiKey
     * @param  string|null  $apiBase
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
     * Get Stripe API Key.
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
     * @param  string  $apiKey
     *
     * @return self
     */
    private function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set API Base URL
     *
     * @param  string|null  $apiBaseUrl
     *
     * @return self
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
     * @return \Arcanedev\Stripe\Contracts\Http\Curl\HttpClientInterface
     */
    private function httpClient()
    {
        // @codeCoverageIgnoreStart
        if ( ! self::$httpClient) {
            self::$httpClient = HttpClient::instance();
        }
        // @codeCoverageIgnoreEnd

        return self::$httpClient;
    }

    /**
     * Set the HTTP client
     *
     * @param  \Arcanedev\Stripe\Contracts\Http\Curl\HttpClientInterface  $client
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
     * Make Requestor instance.
     *
     * @param  string|null  $apiKey
     * @param  string       $apiBase
     *
     * @return self
     */
    public static function make($apiKey = null, $apiBase = '')
    {
        return new self($apiKey, $apiBase);
    }

    /**
     * GET Request.
     *
     * @param  string      $url
     * @param  array|null  $params
     * @param  array|null  $headers
     *
     * @return array
     */
    public function get($url, $params = [], $headers = null)
    {
        return $this->request('get', $url, $params, $headers);
    }

    /**
     * POST Request.
     *
     * @param  string      $url
     * @param  array|null  $params
     * @param  array|null  $headers
     *
     * @return array
     */
    public function post($url, $params = [], $headers = null)
    {
        return $this->request('post', $url, $params, $headers);
    }

    /**
     * DELETE Request.
     *
     * @param  string      $url
     * @param  array|null  $params
     * @param  array|null  $headers
     *
     * @return array
     */
    public function delete($url, $params = [], $headers = null)
    {
        return $this->request('delete', $url, $params, $headers);
    }

    /**
     * Make a request.
     * Note: An array whose first element is the Response object and second element is the API key used to make the request.
     *
     * @param  string      $method
     * @param  string      $url
     * @param  array|null  $params
     * @param  array|null  $headers
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     *
     * @return array
     */
    public function request($method, $url, $params = null, $headers = null)
    {
        if (is_null($params))  $params  = [];
        if (is_null($headers)) $headers = [];

        list($respBody, $respCode, $respHeaders, $apiKey) = $this->requestRaw($method, $url, $params, $headers);

        $json     = $this->interpretResponse($respBody, $respCode, $respHeaders);
        $response = new Response($respBody, $respCode, $headers, $json);

        return [$response, $apiKey];
    }

    /**
     * Raw request.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  array   $params
     * @param  array   $headers
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiConnectionException
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     * @throws \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     *
     * @return array
     */
    private function requestRaw($method, $url, $params, $headers)
    {
        $this->checkApiKey();
        $this->checkMethod($method);

        $params = self::encodeObjects($params);

        $this->httpClient()->setApiKey($this->getApiKey());

        $hasFile = self::processResourceParams($params);

        list($respBody, $respCode, $respHeaders) = $this->httpClient()
            ->request($method, $this->apiBaseUrl . $url, $params, $headers, $hasFile);

        return [$respBody, $respCode, $respHeaders, $this->getApiKey()];
    }

    /**
     * Interpret Response.
     *
     * @param  string  $respBody
     * @param  int     $respCode
     * @param  array   $respHeaders
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     * @throws \Arcanedev\Stripe\Exceptions\AuthenticationException
     * @throws \Arcanedev\Stripe\Exceptions\CardException
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @throws \Arcanedev\Stripe\Exceptions\RateLimitException
     *
     * @return array
     */
    private function interpretResponse($respBody, $respCode, $respHeaders)
    {
        try {
            $response = json_decode($respBody, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception;
            }
        }
        catch (Exception $e) {
            $message = "Invalid response body from API: $respBody (HTTP response code was $respCode)";

            throw new ApiException($message, 500, 'api_error', null, $respBody);
        }

        $this->errorsHandler->handle($respBody, $respCode, $respHeaders, $response);

        return $response;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if API Key Exists.
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     */
    private function checkApiKey()
    {
        if ( ! $this->isApiKeyExists()) {
            throw new ApiKeyNotSetException('The Stripe API Key is required !');
        }
    }

    /**
     * Check if the API Key is set.
     *
     * @return bool
     */
    private function isApiKeyExists()
    {
        $apiKey = $this->getApiKey();

        return ! empty($apiKey);
    }

    /**
     * Check Http Method.
     *
     * @param  string  $method
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
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

    /**
     * Check Resource type is stream.
     *
     * @param  resource  $resource
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkResourceType($resource)
    {
        if (get_resource_type($resource) !== 'stream') {
            throw new ApiException(
                'Attempted to upload a resource that is not a stream'
            );
        }
    }

    /**
     * Check resource MetaData.
     *
     * @param  array  $metaData
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkResourceMetaData(array $metaData)
    {
        if ($metaData['wrapper_type'] !== 'plainfile') {
            throw new ApiException(
                'Only plainfile resource streams are supported'
            );
        }
    }

    /**
     * Check if param is resource File.
     *
     * @param  mixed  $resource
     *
     * @return bool
     */
    private static function checkHasResourceFile($resource)
    {
        return
            is_resource($resource) ||
            (class_exists('CURLFile') && $resource instanceof CURLFile);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Process Resource Parameters.
     *
     * @param  array|string  $params
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     *
     * @return bool
     */
    private static function processResourceParams(&$params)
    {
        // @codeCoverageIgnoreStart
        if ( ! is_array($params)) return false;
        // @codeCoverageIgnoreEnd

        $hasFile = false;

        foreach ($params as $key => $resource) {
            $hasFile = self::checkHasResourceFile($resource);

            if (is_resource($resource)) {
                $params[$key] = self::processResourceParam($resource);
            }
        }

        return $hasFile;
    }

    /**
     * Process Resource Parameter.
     *
     * @param  resource  $resource
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     *
     * @return \CURLFile|string
     */
    private static function processResourceParam($resource)
    {
        self::checkResourceType($resource);

        $metaData = stream_get_meta_data($resource);

        self::checkResourceMetaData($metaData);

        // We don't have the filename or mimetype, but the API doesn't care
        return class_exists('CURLFile')
            ? new CURLFile($metaData['uri'])
            : '@' . $metaData['uri'];
    }

    /**
     * Encode Objects.
     *
     * @param  \Arcanedev\Stripe\StripeResource|bool|array|string  $obj
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     *
     * @return array|string
     */
    private static function encodeObjects($obj)
    {
        if ($obj instanceof StripeResource) {
            return str_utf8($obj->id);
        }

        if (is_bool($obj)) {
            return $obj ? 'true' : 'false';
        }

        if (is_array($obj)) {
            return array_map(function($v) {
                return self::encodeObjects($v);
            }, $obj);
        }

        return str_utf8($obj);
    }
}
