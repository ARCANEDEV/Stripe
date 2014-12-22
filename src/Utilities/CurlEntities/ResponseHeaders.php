<?php namespace Arcanedev\Stripe\Utilities\CurlEntities;

class ResponseHeaders extends CaseInsensitiveArray
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $container = [];

    public $response;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $rawResponseHeaders
     * @param string $rawResponse
     */
    public function __construct($rawResponseHeaders, $rawResponse)
    {
        $this->parseResponseHeaders($rawResponseHeaders);
        $this->setRawResponse($rawResponse);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $rawResponse
     *
     * @return ResponseHeaders
     */
    private function setRawResponse($rawResponse)
    {
        $this->response = $this->parseResponse($rawResponse);

        return $this;
    }

    /**
     * @param array $header
     *
     * @return ResponseHeaders
     */
    private function setHttpMessage($header)
    {
        $this['Status-Line'] = isset($header[0]) ? $header[0] : '';

        return $this;
    }

    public function getHttpMessage()
    {
        return $this->hasHttpErrorMessage() ? $this['Status-Line'] : '';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Parse Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $rawHeaders
     */
    private function parseResponseHeaders($rawHeaders)
    {
        //region Clean the response headers
        $responseHeaderArray = explode("\r\n\r\n", $rawHeaders);
        $responseHeader      = '';

        for ($i = count($responseHeaderArray) - 1; $i >= 0; $i--) {
            if (stripos($responseHeaderArray[$i], 'HTTP/') === 0) {
                $responseHeader = $responseHeaderArray[$i];
                break;
            }
        }
        //endregion

        $responseHeaderArray = preg_split('/\r\n/', $responseHeader, null, PREG_SPLIT_NO_EMPTY);

        $this->setHttpMessage($responseHeaderArray);

        $this->populateContainer($responseHeaderArray);
    }

    private function populateContainer($responseHeaders)
    {
        array_map(function($header) {
            $this->addOneContainer(array_map('trim', explode(':', $header, 2)));
        }, $responseHeaders);
    }

    /**
     * @param array $header
     */
    private function addOneContainer($header)
    {
        if (count($header) != 2) {
            return;
        }

        list($key, $value) = $header;

        if (array_key_exists($key, $this->container)) {
            $this->container[$key] .= ',' . $value;
            return;
        }

        $this->container[$key] = $value;
    }

    /**
     * @param string $rawResponse
     *
     * @return mixed|\SimpleXMLElement
     */
    private function parseResponse($rawResponse)
    {
        if (! $this->hasContentType()) {
            return $rawResponse;
        }

        if ($this->isJsonType()) {
            $json = json_decode($rawResponse, false);
            if ($json !== null) {
                return $json;
            }
        }

        if ($this->isXmlType()) {
            $xml = @simplexml_load_string($rawResponse);
            if (! ($xml === false)) {
                return $xml;
            }
        }

        return $rawResponse;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return bool
     */
    public function hasHttpErrorMessage()
    {
        return isset($this['Status-Line']);
    }

    /**
     * @return bool
     */
    private function hasContentType()
    {
        return isset($this['Content-Type']);
    }

    /**
     * @return bool
     */
    private function isJsonType()
    {
        return $this->checkContentType('~^application/(?:json|vnd\.api\+json)~i');
    }

    /**
     * @return bool
     */
    private function isXmlType()
    {
        return $this->checkContentType('~^(?:text/|application/(?:atom\+|rss\+)?)xml~i');
    }

    /**
     * @param string $pattern
     *
     * @return bool
     */
    private function checkContentType($pattern)
    {
        return (bool) preg_match($pattern, $this['Content-Type']);
    }
}
