<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Contracts\Utilities\HttpRequestInterface;
use Arcanedev\Stripe\Utilities\CurlEntities\RequestHeaders;
use Arcanedev\Stripe\Utilities\CurlEntities\ResponseHeaders;
use Arcanedev\Stripe\Utilities\CurlEntities\ResponseObject;

class CurlRequest implements HttpRequestInterface
{
    // TODO: Complete CurlRequest Implementation
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var resource|null */
    private $handle             = null;
    /** @var array */
    private $options            = [];

    /** @var string */
    public $baseUrl             = null;
    /** @var string */
    public $url                 = null;

    /** @var RequestHeaders */
    private $reqHeaders;

    /** @var ResponseHeaders */
    private $respHeaders;

    /** @var ResponseObject */
    public $response            = null;

    /** @var bool */
    public $curlError           = false;
    /** @var int */
    public $curlErrorNum;
    /** @var string */
    public $curlErrorMsg        = '';

    /** @var bool */
    public $httpError           = false;
    /** @var int */
    public $httpStatusCode;
    /** @var string */
    public $httpErrorMsg        = '';

    /** @var bool */
    public $error               = false;
    /** @var int */
    public $errorCode;
    /** @var string */
    private $errorMsg           = '';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor & Destructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($url = null)
    {
        $this->handle       = curl_init($url);
        $this->rawResponse  = false;
        $this->curlErrorMsg = '';
        $this->curlErrorNum = 0;

        $this->setDefaultOptions();
    }

    /**
     * Set Default Options
     */
    private function setDefaultOptions()
    {
        $this->setOptions([
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 80,
            CURLOPT_RETURNTRANSFER => true,
        ]);
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
     * Set URL
     *
     * @param string $url
     *
     * @return CurlRequest
     */
    public function url($url)
    {
        return $this->setUrl($url);
    }

    /**
     * Set URL
     *
     * @param string $url
     *
     * @return CurlRequest
     */
    public function setUrl($url)
    {
        $url = $this->encodeUrl($url);

        return $this->setOption(CURLOPT_URL, $url);
    }

    /**
     * * Set CURL option
     *
     * @param int   $name
     * @param mixed $value
     *
     * @return CurlRequest
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Set CURL options
     *
     * @param array $options
     *
     * @return CurlRequest
     */
    public function setOptions(array $options)
    {
        $this->options += $options;

        return $this;
    }

    /**
     * @param $headers
     *
     * @return CurlRequest
     */
    public function setHttpHeaders($headers)
    {
        return $this->setOption(CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * Get Response Object
     *
     * @return ResponseObject
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Convert response to array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->hasResponse()
            ? $this->getResponse()->toArray() : [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  HTTP Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function get($url, $data = [])
    {
        $this->baseUrl = $url;
        $this->url     = $this->buildURL($url, $data);
        $this->setOption(CURLOPT_URL, $this->url);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'GET');
        $this->setOption(CURLOPT_HTTPGET, true);

        return $this->execute();
    }

    //public function post($url, $data = [])
    //{
    //    $this->setOption(CURLOPT_POST, true);
    //    $this->setOption(CURLOPT_CUSTOMREQUEST, 'POST');
    //    $this->setOption(CURLOPT_POSTFIELDS, $this->postData($data));
    //
    //    return $this->execute();
    //}
    //
    //public function delete($url, $data = [])
    //{
    //    $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
    //
    //    return $this->execute();
    //}

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the CURL handler
     *
     * @return mixed
     */
    public function execute()
    {
        //region Set Response Header File Option
        $responseHeadersFH = fopen('php://temp', 'rw+');
        $this->setOption(CURLOPT_WRITEHEADER, $responseHeadersFH);
        //endregion

        if ($this->hasOptions()) {
            curl_setopt_array($this->handle, $this->options);
        }

        $rawResponse = curl_exec($this->handle);

        //region Get Response Header Option
        rewind($responseHeadersFH);
        $rawResponseHeaders = stream_get_contents($responseHeadersFH);
        fclose($responseHeadersFH);
        //endregion

        //region Request Stuff
        $rawRequestHeaders = $this->getCurlInfo(CURLINFO_HEADER_OUT);
        $this->reqHeaders  = new RequestHeaders($rawRequestHeaders);
        //endregion

        //region Response
        $this->respHeaders = new ResponseHeaders($rawResponseHeaders, $rawResponse);
        $this->response    = $this->respHeaders->response;
        //endregion

        $this->setErrorCodeAndMessages();

        return $this->response;
    }

    /**
     * Set Error Code And Messages From CURL
     *
     * @return CurlRequest
     */
    private function setErrorCodeAndMessages()
    {
        $this->setCurlErrorNum()
             ->setCurlErrorMessage()
             ->setHttpStatusCode()
             ->setErrorCodeAndMessage();
    }

    /**
     * Get CURL Info
     *
     * @param int $name
     *
     * @return mixed
     */
    private function getCurlInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    /**
     * Get last Error Number from CURL
     *
     * @return int
     */
    public function getCurlErrorNumber()
    {
        return curl_errno($this->handle);
    }

    /**
     * Set last Error Number form CURL
     *
     * @return CurlRequest
     */
    private function setCurlErrorNum()
    {
        $this->curlErrorNum = $this->getCurlErrorNumber();
        $this->curlError    = ! ($this->curlErrorNum === 0);

        return $this;
    }

    /**
     * Get last Error Message from CURL
     *
     * @return string
     */
    private function getCurlErrorMessage()
    {
        return curl_error($this->handle);
    }

    /**
     * Set last Error Message from CURL
     *
     * @return CurlRequest
     */
    private function setCurlErrorMessage()
    {
        $this->curlErrorMsg = $this->getCurlErrorMessage();

        return $this;
    }

    /**
     * Get Status Code from CURL
     *
     * @return int
     */
    private function getHttpStatusCode()
    {
        return $this->getCurlInfo(CURLINFO_HTTP_CODE);
    }

    /**
     * Set Http Status Code from CURL
     *
     * @return CurlRequest
     */
    private function setHttpStatusCode()
    {
        $this->httpStatusCode = $this->getHttpStatusCode();
        $this->httpError      = in_array(floor($this->httpStatusCode / 100), [4, 5]);

        return $this;
    }

    /**
     * Set Http Message form Http Response Object
     */
    private function setHttpMessage()
    {
        if ($this->error and $this->respHeaders->hasHttpErrorMessage()) {
            $this->httpErrorMsg = $this->respHeaders->getHttpMessage();
        }
    }

    /**
     * Set Error Code and Status from CURL & Http Status
     *
     * @return CurlRequest
     */
    private function setErrorCodeAndMessage()
    {
        $this->error            = $this->curlError || $this->httpError;

        $this->errorCode        = $this->error
            ? ($this->curlError ? $this->curlErrorNum : $this->httpStatusCode) : 0;

        $this->setHttpMessage();

        $this->errorMsg         = $this->curlError
            ? $this->curlErrorMsg : $this->httpErrorMsg;

        return $this;
    }

    /**
     * Close curl handler
     */
    public function close()
    {
        if (is_resource($this->handle)) {
            curl_close($this->handle);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  SSL Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has SSL Errors
     *
     * @return bool
     */
    public function hasSSLErrors()
    {
        return in_array($this->curlErrorNum, [
            CURLE_SSL_CACERT,
            CURLE_SSL_PEER_CERTIFICATE,
            CURLE_SSL_CACERT_BADFILE,
        ]);
    }

    /**
     * Enable CURL SSL Verify Peer
     *
     * @return CurlRequest
     */
    public function enableSSLVerifyPeer()
    {
        return $this->toggleSSLVerifyPeer(true);
    }

    /**
     * Disable CURL SSL Verify Peer
     *
     * @return CurlRequest
     */
    public function disableSSLVerifyPeer()
    {
        return $this->toggleSSLVerifyPeer(false);
    }

    /**
     * Toggle SSL Verify Peer
     *
     * @param bool $verify
     *
     * @return CurlRequest
     */
    private function toggleSSLVerifyPeer($verify = true)
    {
        return $this->setOption(CURLOPT_SSL_VERIFYPEER, $verify);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has response
     *
     * @return bool
     */
    public function hasResponse()
    {
        return ! is_null($this->response) and ! $this->response->isEmpty();
    }

    /**
     * Check if has options
     *
     * @return bool
     */
    public function hasOptions()
    {
        return (bool) count($this->options);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare POST Data
     *
     * @param array $data
     *
     * @return array|string
     */
    protected function postData($data)
    {
        if (is_array($data)) {
            if (self::isMultiDimArray($data)) {
                $data = self::buildHttpMultiQuery($data);
            }
            else {
                $binary_data = false;
                foreach ($data as $key => $value) {
                    // Fix "Notice: Array to string conversion" when $value in
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $value) is an array
                    // that contains an empty array.
                    if (is_array($value) && empty($value)) {
                        $data[$key] = '';
                        // Fix "curl_setopt(): The usage of the @filename API for
                        // file uploading is deprecated. Please use the CURLFile
                        // class instead".
                    }
                    elseif (is_string($value) && strpos($value, '@') === 0) {
                        $binary_data = true;
                        if (class_exists('CURLFile')) {
                            $data[$key] = new \CURLFile(substr($value, 1));
                        }
                    }
                    elseif ($value instanceof \CURLFile) {
                        $binary_data = true;
                    }
                }

                if (! $binary_data) {
                    $data = http_build_query($data);
                }
            }
        }

        return $data;
    }

    /**
     * Build Http Multi Query string
     *
     * @param array       $data
     * @param string|null $key
     *
     * @return string
     */
    private static function buildHttpMultiQuery($data, $key = null)
    {
        if (empty($data)) {
            return $key . '=';
        }

        $is_array_assoc = self::isAssocArray($data);

        $query = [];

        foreach ($data as $k => $value) {
            if (is_string($value) or is_numeric($value)) {
                $brackets = $is_array_assoc ? '[' . $k . ']' : '[]';
                $query[]  = urlencode(is_null($key) ? $k : $key . $brackets) . '=' . rawurlencode($value);
            }
            elseif (is_array($value)) {
                $nested  = is_null($key) ? $k : $key . '[' . $k . ']';
                $query[] = self::buildHttpMultiQuery($value, $nested);
            }
        }

        return implode('&', $query);
    }

    /**
     * Check if array is a multidimensional array
     *
     * @param array $array
     *
     * @return bool
     */
    private static function isMultiDimArray($array)
    {
        return is_multi_dim_array($array);
    }

    /**
     * Check if array is an associative array
     *
     * @param $array
     *
     * @return bool
     */
    private static function isAssocArray($array)
    {
        return is_assoc_array($array);
    }

    /**
     * Build URL with queries
     *
     * @param string $url
     * @param array  $data
     *
     * @return string
     */
    private function buildURL($url, $data = [])
    {
        $url .= empty($data) ? '' : '?' . http_build_query($data);

        return $url;
    }

    /**
     * Encode URL in utf-8
     *
     * @param $url
     *
     * @return string
     */
    private function encodeUrl($url)
    {
        if (! is_string($url)) {
            // TODO: Add an Exception
        }

        if (is_string($url) and mb_detect_encoding($url, "UTF-8", true) != "UTF-8") {
            $url = utf8_encode($url);
        }

        return $url;
    }
}
