<?php namespace Arcanedev\Stripe\Http\Curl;

use Arcanedev\Stripe\Contracts\Http\Curl\HttpClientInterface;
use Arcanedev\Stripe\Exceptions\ApiConnectionException;

/**
 * Class     HttpClient
 *
 * @package  Arcanedev\Stripe\Http\Curl
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HttpClient implements HttpClientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const DEFAULT_TIMEOUT = 80;
    const DEFAULT_CONNECT_TIMEOUT = 30;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The HTTP Client instance.
     *
     * @var \Arcanedev\Stripe\Http\Curl\HttpClient
     */
    private static $instance;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiBaseUrl;

    /**
     * @var \Arcanedev\Stripe\Http\Curl\HeaderBag
     */
    private $headers;

    /**
     * @var \Arcanedev\Stripe\Http\Curl\CurlOptions
     */
    private $options;

    /**
     * @var resource
     */
    private $curl;

    /**
     * @var int
     */
    private $timeout = self::DEFAULT_TIMEOUT;

    /**
     * @var int
     */
    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;

    /**
     * @var mixed
     */
    private $response;

    /**
     * @var int
     */
    private $errorCode;

    /**
     * @var string
     */
    private $errorMessage;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor & Destructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a HttpClient instance.
     */
    private function __construct()
    {
        $this->headers  = new HeaderBag;
        $this->options  = new CurlOptions;
        $this->response = null;
    }

    /**
     * Destroy the instance.
     */
    public function __destruct()
    {
        $this->close();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set API Key.
     *
     * @param  string  $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set Base URL.
     *
     * @param  string  $apiBaseUrl
     *
     * @return self
     */
    public function setApiBaseUrl($apiBaseUrl)
    {
        $this->apiBaseUrl = $apiBaseUrl;

        return $this;
    }

    /**
     * Get the timeout.
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set the timeout.
     *
     * @param  int  $seconds
     *
     * @return self
     */
    public function setTimeout($seconds)
    {
        $this->timeout = (int) max($seconds, 0);

        return $this;
    }

    /**
     * Get the connect timeout.
     *
     * @return int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * Set the connect timeout.
     *
     * @param  int  $seconds
     *
     * @return self
     */
    public function setConnectTimeout($seconds)
    {
        $this->connectTimeout = (int) max($seconds, 0);

        return $this;
    }

    /**
     * Get array options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options->get();
    }

    /**
     * Set array options.
     *
     * @param  array  $options
     *
     * @return self
     */
    public function setOptionArray(array $options)
    {
        $this->options->setOptions($options);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Curl Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Init curl.
     */
    private function init()
    {
        $this->curl = curl_init();
    }

    /**
     * Execute curl.
     */
    private function execute()
    {
        curl_setopt_array($this->curl, $this->getOptions());
        $this->response     = curl_exec($this->curl);
        $this->errorCode    = curl_errno($this->curl);
        $this->errorMessage = curl_error($this->curl);
    }

    /**
     * Close curl.
     */
    private function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make the HTTP Client with options.
     *
     * @param  array  $options
     *
     * @return static
     */
    public static function make(array $options = [])
    {
        return (new static)->setOptionArray($options);
    }

    /**
     * Get the HTTP.
     *
     * @return self
     */
    public static function instance()
    {
        if ( ! self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Curl the request.
     *
     * @param  string        $method
     * @param  string        $url
     * @param  array|string  $params
     * @param  array         $headers
     * @param  bool          $hasFile
     *
     * @return array
     */
    public function request($method, $url, $params, $headers, $hasFile)
    {
        if ($method !== 'post') {
            $url    = str_parse_url($url, $params);
        }
        else {
            $params = $hasFile ? $params : self::encode($params);
        }

        $this->headers->prepare($this->apiKey, $headers, $hasFile);
        $this->options->make($method, $url, $params, $this->headers->get(), $hasFile);
        $this->setOptionArray([
            CURLOPT_CONNECTTIMEOUT => $this->connectTimeout,
            CURLOPT_TIMEOUT        => $this->timeout,
        ]);

        $respHeaders = [];
        $this->prepareResponseHeaders($respHeaders);

        $this->init();
        $this->execute();
        $this->checkCertErrors();
        $this->checkResponse();

        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->close();

        return [$this->response, $statusCode, $respHeaders];
    }

    /**
     * Check Cert Errors.
     */
    private function checkCertErrors()
    {
        if (SslChecker::hasCertErrors($this->errorCode)) {
            $this->headers->set(
                'X-Stripe-Client-Info',
                '{"ca":"using Stripe-supplied CA bundle"}'
            );

            $this->setOptionArray([
                CURLOPT_HTTPHEADER => $this->headers->get(),
                CURLOPT_CAINFO     => SslChecker::caBundle()
            ]);

            $this->execute();
        }
    }

    /**
     * Encode array to query string
     *
     * @param  array|string  $array
     * @param  string|null   $prefix
     *
     * @return string
     */
    protected static function encode($array, $prefix = null)
    {
        // @codeCoverageIgnoreStart
        if ( ! is_array($array)) return $array;
        // @codeCoverageIgnoreEnd

        $result = [];

        foreach ($array as $key => $value) {
            if (is_null($value)) continue;

            if ($prefix) {
                $key = ($key !== null && (! is_int($key) || is_array($value)))
                    ? $prefix . "[" . $key . "]"
                    : $prefix . "[]";
            }

            if ( ! is_array($value)) {
                $result[] = urlencode($key) . '=' . urlencode($value);
            }
            elseif ($enc = self::encode($value, $key)) {
                $result[] = $enc;
            }
        }

        return implode('&', $result);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check Response.
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiConnectionException
     */
    private function checkResponse()
    {
        if ($this->response !== false) return;

        $this->close();
        $this->handleCurlError();
    }

    /**
     * Handle CURL errors.
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiConnectionException
     */
    private function handleCurlError()
    {
        switch ($this->errorCode) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Stripe ({$this->apiBaseUrl}). Please check your internet connection "
                    . 'and try again.  If this problem persists, you should check Stripe\'s service status at '
                    . 'https://twitter.com/stripestatus, or';
                break;

            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = 'Could not verify Stripe\'s SSL certificate.  Please make sure that your network is not '
                    . "intercepting certificates. (Try going to {$this->apiBaseUrl} in your browser.) "
                    . 'If this problem persists,';
                break;

            default:
                $msg = 'Unexpected error communicating with Stripe. If this problem persists,';
                // no break
        }

        $msg .= ' let us know at support@stripe.com.';

        $msg .= "\n\n(Network error [errno {$this->errorCode}]: {$this->errorMessage})";

        throw new ApiConnectionException($msg);
    }

    /**
     * Prepare Response Headers.
     *
     * @return array
     */
    private function prepareResponseHeaders(array &$respHeaders)
    {
        $this->options->setOption(CURLOPT_HEADERFUNCTION, function ($curl, $header_line) use (&$respHeaders) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (strpos($header_line, ":") === false) {
                return strlen($header_line);
            }

            list($key, $value) = explode(":", trim($header_line), 2);
            $respHeaders[trim($key)] = trim($value);
            return strlen($header_line);
        });
    }
}
