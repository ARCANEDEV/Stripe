<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\RequestorInterface;
use Arcanedev\Stripe\Exceptions\ApiConnectionException;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\ApiKeyNotSetException;
use Arcanedev\Stripe\Exceptions\AuthenticationException;
use Arcanedev\Stripe\Exceptions\CardException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Exceptions\RateLimitException;
use Arcanedev\Stripe\Utilities\ErrorsHandler;
use CURLFile;

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

    private $apiBaseUrl;

    private static $preFlight      = [];

    private static $allowedMethods = [
        'get', 'post', 'delete'
    ];

    /** @var bool */
    private $hasFile = false;

    /** @var ErrorsHandler */
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
        $this->errorsHandler = new ErrorsHandler;
        $this->setApiKey($apiKey);
        $this->setApiBase($apiBase);
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
        if (! $this->apiKey) {
            $this->setApiKey(Stripe::getApiKey());
        }

        return trim($this->apiKey);
    }

    /**
     * Set API Key
     *
     * @param string $apiKey
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
     * @param string|null $apiBaseUrl
     *
     * @return Requestor
     */
    private function setApiBase($apiBaseUrl)
    {
        if (! $apiBaseUrl) {
            $apiBaseUrl = Stripe::getApiBaseUrl();
        }

        $this->apiBaseUrl = $apiBaseUrl;

        return $this;
    }

    /**
     * Get the certificates file path
     *
     * @return string
     */
    private function caBundle()
    {
        return ca_certificates();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Requestor with static method
     *
     * @param string|null $apiKey
     * @param string      $apiBase
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
     * @param string     $url
     * @param array|null $params
     * @param array|null $headers
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
     * @param string     $url
     * @param array|null $params
     * @param array|null $headers
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
     * @param string     $url
     * @param array|null $params
     * @param array|null $headers
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
     * @param string     $method
     * @param string     $url
     * @param array|null $params
     * @param array|null $headers
     *
     * @throws ApiException
     *
     * @return array
     */
    public function request($method, $url, $params = [], $headers = null)
    {
        $this->checkApiKey();
        $this->checkMethod($method);

        if (! is_array($params) or is_null($params)) {
            $params = [];
        }

        if (is_null($headers)) {
            $headers = [];
        }

        list($respBody, $respCode, $apiKey) =
            $this->requestRaw($method, $url, $params, $headers);

        $response = $this->interpretResponse($respBody, $respCode);

        return [$response, $apiKey];
    }

    /**
     * Interpret Response
     *
     * @param string $respBody
     * @param int    $respCode
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
     * @param string $method
     * @param string $url
     * @param array  $params
     * @param array  $headers
     *
     * @throws ApiConnectionException
     * @throws ApiException
     * @throws ApiKeyNotSetException
     *
     * @return array
     */
    private function requestRaw($method, $url, $params, $headers)
    {
        if (
            ! array_key_exists($this->apiBaseUrl, self::$preFlight) or
            ! self::$preFlight[$this->apiBaseUrl]
        ) {
            self::$preFlight[$this->apiBaseUrl] = $this->checkSslCert($this->apiBaseUrl);
        }

        $apiKey = $this->getApiKey();
        $absUrl = $this->apiBaseUrl . $url;
        $params = self::encodeObjects($params);

        list($respBody, $respCode) =
            $this->curlRequest($method, $absUrl, $params, $headers);

        return [$respBody, $respCode, $apiKey];
    }

    /**
     * Curl the request
     *
     * @param string $method
     * @param string $absUrl
     * @param array  $params
     * @param array  $headers
     *
     * @return array
     * @throws ApiConnectionException
     * @throws ApiException
     */
    private function curlRequest($method, $absUrl, $params, $headers)
    {
        self::processResourceParams($params);

        if ($method !== 'post') {
            $absUrl = str_parse_url($absUrl, $params);
        }
        else {
            $params = $this->hasFile ? $params : str_url_queries($params);
        }

        $headers  = $this->prepareCurlHeaders($headers);
        $opts     = $this->prepareCurlOptions($method, $absUrl, $params, $headers);

        $curl     = curl_init();
        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        $errorNum = curl_errno($curl);

        if (
            $errorNum == CURLE_SSL_CACERT or
            $errorNum == CURLE_SSL_PEER_CERTIFICATE or
            $errorNum == CURLE_SSL_CACERT_BADFILE
        ) {
            array_push(
                $headers,
                'X-Stripe-Client-Info: {"ca":"using Stripe-supplied CA bundle"}'
            );

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_CAINFO, $this->caBundle());

            $response = curl_exec($curl);
        }

        if ($response === false) {
            $errorNum = curl_errno($curl);
            $message  = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($errorNum, $message);
        }

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [$response, $statusCode];
    }

    /**
     * Process Resource Parameters
     *
     * @param array $params
     *
     * @throws ApiException
     */
    private function processResourceParams(&$params)
    {
        foreach ($params as $key => $resource) {
            $this->hasFile = self::checkHasResourceFile($resource);

            if (is_resource($resource)) {
                $params[$key] = self::processResourceParam($resource);
            }
        }
    }

    /**
     * Process Resource Parameter
     *
     * @param resource $resource
     *
     * @throws ApiException
     *
     * @return CURLFile|string
     */
    private function processResourceParam($resource)
    {
        if (get_resource_type($resource) !== 'stream') {
            throw new ApiException(
                'Attempted to upload a resource that is not a stream'
            );
        }

        $metaData = stream_get_meta_data($resource);
        if ($metaData['wrapper_type'] !== 'plainfile') {
            throw new ApiException(
                'Only plainfile resource streams are supported'
            );
        }

        // We don't have the filename or mimetype, but the API doesn't care
        return class_exists('CURLFile')
            ? new CURLFile($metaData['uri'])
            : '@' . $metaData['uri'];
    }

    /**
     * Get User Agent (JSON format)
     *
     * @return string
     */
    private static function userAgent()
    {
        return json_encode([
            'bindings_version' => Stripe::VERSION,
            'lang'             => 'php',
            'lang_version'     => phpversion(),
            'publisher'        => 'stripe',
            'uname'            => php_uname(),
        ]);
    }

    /**
     * Prepare CURL request Headers
     *
     * @param array $headers
     *
     * @return array
     */
    private function prepareCurlHeaders(array $headers)
    {
        $defaults = [
            'X-Stripe-Client-User-Agent' => self::userAgent(),
            'User-Agent'                 => 'Stripe/v1 PhpBindings/' . Stripe::VERSION,
            'Authorization'              => 'Bearer ' . $this->getApiKey(),
            'Content-Type'               => $this->hasFile
                ? 'multipart/form-data'
                : 'application/x-www-form-urlencoded',
        ];

        if (Stripe::hasApiVersion()) {
            $defaults['Stripe-Version'] = Stripe::getApiVersion();
        }

        $rawHeaders      = [];

        foreach (array_merge($defaults, $headers) as $header => $value) {
            $rawHeaders[] = $header . ': ' . $value;
        }

        return $rawHeaders;
    }

    /**
     * Prepare CURL request Options
     *
     * @param string $method
     * @param string $absUrl
     * @param array  $params
     * @param array  $headers
     *
     * @throws ApiException
     *
     * @return array
     */
    private function prepareCurlOptions($method, $absUrl, $params, $headers)
    {
        $opts = $this->prepareMethodOptions($method, $params);

        $opts[CURLOPT_URL]            = str_utf8($absUrl);
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT]        = 80;
        $opts[CURLOPT_HTTPHEADER]     = $headers;

        if (! Stripe::$verifySslCerts) {
            $opts[CURLOPT_SSL_VERIFYPEER] = false;
        }

        return $opts;
    }

    /**
     * Prepare Method Options
     *
     * @param string $method
     * @param array  $params
     *
     * @throws ApiException
     *
     * @return array
     */
    private function prepareMethodOptions($method, $params)
    {
        $options = [];

        switch ($method) {
            case 'post':
                $options[CURLOPT_POST]          = true;
                $options[CURLOPT_CUSTOMREQUEST] = 'POST';
                $options[CURLOPT_POSTFIELDS]    = $params;
                break;

            case 'delete':
                $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                break;

            case 'get':
            default:
                if ($this->hasFile) {
                    throw new ApiException(
                        'Issuing a GET request with a file parameter'
                    );
                }
                $options[CURLOPT_HTTPGET]       = true;
                $options[CURLOPT_CUSTOMREQUEST] = 'GET';
        }

        return $options;
    }

    /**
     * Handle CURL error
     *
     * @param int    $errorNum
     * @param string $message
     *
     * @throws ApiConnectionException
     */
    private function handleCurlError($errorNum, $message)
    {
        $apiBase = $this->apiBaseUrl;

        switch ($errorNum) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = 'Could not connect to Stripe (' . $apiBase . '). Please check your internet connection '
                    . 'and try again.  If this problem persists, you should check Stripe\'s service status at '
                    . 'https://twitter.com/stripestatus, or';
                break;

            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = 'Could not verify Stripe\'s SSL certificate.  Please make sure that your network is not '
                    . 'intercepting certificates. (Try going to ' . $apiBase . ' in your browser.) '
                    . 'If this problem persists,';
                break;

            default:
                $msg = 'Unexpected error communicating with Stripe. If this problem persists,';
        }

        $msg .= ' let us know at support@stripe.com.';

        $msg .= "\n\n(Network error [errno $errorNum]: $message)";

        throw new ApiConnectionException($msg);
    }

    /**
     * Preflight the SSL certificate presented by the backend. This isn't 100%
     * bulletproof, in that we're not actually validating the transport used to
     * communicate with Stripe, merely that the first attempt to does not use a
     * revoked certificate.
     *
     * Unfortunately the interface to OpenSSL doesn't make it easy to check the
     * certificate before sending potentially sensitive data on the wire. This
     * approach raises the bar for an attacker significantly.
     *
     * @param string $url
     *
     * @throws ApiConnectionException
     *
     * @return bool
     */
    private function checkSslCert($url)
    {
        if (
            ! function_exists('stream_context_get_params') or
            ! function_exists('stream_socket_enable_crypto')
        ) {
            error_log(
                'Warning: This version of PHP does not support checking SSL '.
                'certificates Stripe cannot guarantee that the server has a '.
                'certificate which is not blacklisted.'
            );

            return true;
        }

        $url  = parse_url($url);
        $port = isset($url['port']) ? $url['port'] : 443;
        $url  = "ssl://{$url['host']}:{$port}";

        $sslContext = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer'       => true,
                'cafile'            => $this->caBundle(),
            ]
        ]);

        $result = stream_socket_client($url, $errorNo, $errorStr, 30, STREAM_CLIENT_CONNECT, $sslContext);

        if (
            ($errorNo !== 0 and $errorNo !== null) or
            $result === false
        ) {
            throw new ApiConnectionException(
                "Could not connect to Stripe ($url).  Please check your ".
                'internet connection and try again.  If this problem persists, '.
                'you should check Stripe\'s service status at '.
                'https://twitter.com/stripestatus. Reason was: '. $errorStr
            );
        }

        $params = stream_context_get_params($result);

        $cert   = $params['options']['ssl']['peer_certificate'];

        openssl_x509_export($cert, $pemCert);

        if (self::isBlackListed($pemCert)) {
            throw new ApiConnectionException(
                'Invalid server certificate. You tried to connect to a server that '.
                'has a revoked SSL certificate, which means we cannot securely send '.
                'data to that server.  Please email support@stripe.com if you need '.
                'help connecting to the correct API server.'
            );
        }

        return true;
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

    /**
     * Checks if a valid PEM encoded certificate is blacklisted
     *
     * @param string $cert
     *
     * @return bool
     */
    public static function isBlackListed($cert)
    {
        $cert  = trim($cert);
        $lines = explode("\n", $cert);

        // Kludgily remove the PEM padding
        array_shift($lines);
        array_pop($lines);

        $derCert     = base64_decode(implode('', $lines));
        $fingerprint = sha1($derCert);

        return in_array($fingerprint, [
            '05c0b3643694470a888c6e7feb5c9e24e823dc53',
            '5b7dc7fbc98d78bf76d4d4fa6f597a0c901fad5c',
        ]);
    }

    /**
     * Check if param is resource File
     *
     * @param mixed $resource
     *
     * @return bool
     */
    private function checkHasResourceFile($resource)
    {
        return is_resource($resource) or
        (class_exists('CURLFile') and $resource instanceof CURLFile);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Encode Objects
     *
     * @param Resource|bool|array|string $obj
     *
     * @throws ApiException
     *
     * @return array|string
     */
    private static function encodeObjects($obj)
    {
        if ($obj instanceof Resource) {
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
        if (! $this->isApiKeyExists()) {
            throw new ApiKeyNotSetException(
                'The Stripe API Key is required !'
            );
        }
    }

    /**
     * Check Http Method
     *
     * @param string $method
     *
     * @throws ApiException
     */
    private function checkMethod(&$method)
    {
        $method = strtolower($method);

        if (in_array($method, self::$allowedMethods)) {
            return;
        }

        $methods = implode(', ', self::$allowedMethods);

        throw new ApiException(
            "Unrecognized method $method, must be [$methods].",
            500
        );
    }
}
