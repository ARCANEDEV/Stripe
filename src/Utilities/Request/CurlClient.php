<?php namespace Arcanedev\Stripe\Utilities\Request;

use Arcanedev\Stripe\Contracts\Utilities\Request\CurlClientInterface;
use Arcanedev\Stripe\Exceptions\ApiConnectionException;
use Arcanedev\Stripe\Exceptions\ApiException;
use CURLFile;

class CurlClient implements CurlClientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    private $apiKey;

    /** @var string */
    private $apiBaseUrl;

    /** @var HeaderBag */
    private $headers;

    /** @var CurlOptions */
    private $options;

    /** @var resource */
    private $curl;

    /** @var mixed */
    private $response;

    /** @var int */
    private $errorCode;

    /** @var string */
    private $errorMessage;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor & Destructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $apiKey
     * @param string $baseUrl
     */
    public function __construct($apiKey, $baseUrl)
    {
        $this->setApiKey($apiKey);
        $this->setApiBaseUrl($baseUrl);

        $this->headers  = new HeaderBag;
        $this->options  = new CurlOptions;
        $this->response = null;
    }

    public function __destruct()
    {
        $this->close();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set API Key
     *
     * @param  string $apiKey
     *
     * @return CurlClient
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set Base URL
     *
     * @param  string $apiBaseUrl
     *
     * @return CurlClient
     */
    public function setApiBaseUrl($apiBaseUrl)
    {
        $this->apiBaseUrl = $apiBaseUrl;

        return $this;
    }

    /**
     * Set array options
     *
     * @param  array $options
     *
     * @return CurlClient
     */
    public function setOptionArray(array $options)
    {
        curl_setopt_array($this->curl, $options);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Curl Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Init curl
     */
    private function init()
    {
        $this->curl = curl_init();
    }

    /**
     * Execute curl
     */
    private function execute()
    {
        $this->response     = curl_exec($this->curl);
        $this->errorCode    = curl_errno($this->curl);
        $this->errorMessage = curl_error($this->curl);
    }

    /**
     * Close curl
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
     * Curl the request
     *
     * @param  string       $method
     * @param  string       $url
     * @param  array|string $params
     * @param  array        $headers
     *
     * @throws ApiConnectionException
     * @throws ApiException
     *
     * @return array
     */
    public function request($method, $url, $params, $headers)
    {
        $hasFile = self::processResourceParams($params);

        if ($method !== 'post') {
            $url = str_parse_url($url, $params);
        }
        else {
            $params = $hasFile ? $params : str_url_queries($params);
        }

        $headers  = $this->headers->make($this->apiKey, $headers, $hasFile);
        $options  = $this->options->make($method, $url, $params, $headers, $hasFile);

        $this->init();
        $this->setOptionArray($options->get());
        $this->execute();

        if (SslChecker::hasCertErrors($this->errorCode)) {
            array_push(
                $headers,
                'X-Stripe-Client-Info: {"ca":"using Stripe-supplied CA bundle"}'
            );
            // TODO: Try this instead
            //$this->headers->set(
            //    'X-Stripe-Client-Info',
            //    '{"ca":"using Stripe-supplied CA bundle"}'
            //);

            $this->setOptionArray([
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_CAINFO     => SslChecker::caBundle()
            ]);

            $this->execute();
        }

        $this->checkResponse();

        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->close();

        return [$this->response, $statusCode];
    }

    /**
     * Process Resource Parameters
     *
     * @param  array|string $params
     *
     * @throws ApiException
     *
     * @return bool
     */
    private static function processResourceParams(&$params)
    {
        if (! is_array($params)) {
            return false;
        }

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
     * Process Resource Parameter
     *
     * @param resource $resource
     *
     * @throws ApiException
     *
     * @return CURLFile|string
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

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check Resource type is stream
     *
     * @param resource $resource
     *
     * @throws ApiException
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
     * Check resource MetaData
     *
     * @param  array $metaData
     *
     * @throws ApiException
     */
    private static function checkResourceMetaData($metaData)
    {
        if ($metaData['wrapper_type'] !== 'plainfile') {
            throw new ApiException(
                'Only plainfile resource streams are supported'
            );
        }
    }

    /**
     * Check if param is resource File
     *
     * @param mixed $resource
     *
     * @return bool
     */
    private static function checkHasResourceFile($resource)
    {
        return is_resource($resource) or (
            class_exists('CURLFile') and
            $resource instanceof CURLFile
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check Response
     *
     * @throws ApiConnectionException
     */
    private function checkResponse()
    {
        if ($this->response !== false) {
            return;
        }

        $this->close();

        $this->handleCurlError();
    }

    /**
     * Handle CURL error
     *
     * @throws ApiConnectionException
     */
    private function handleCurlError()
    {
        switch ($this->errorCode) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = 'Could not connect to Stripe (' . $this->apiBaseUrl . '). Please check your internet connection '
                    . 'and try again.  If this problem persists, you should check Stripe\'s service status at '
                    . 'https://twitter.com/stripestatus, or';
                break;

            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = 'Could not verify Stripe\'s SSL certificate.  Please make sure that your network is not '
                    . 'intercepting certificates. (Try going to ' . $this->apiBaseUrl . ' in your browser.) '
                    . 'If this problem persists,';
                break;

            default:
                $msg = 'Unexpected error communicating with Stripe. If this problem persists,';
        }

        $msg .= ' let us know at support@stripe.com.';

        $msg .= "\n\n(Network error [errno {$this->errorCode}]: {$this->errorMessage})";

        throw new ApiConnectionException($msg);
    }
}
