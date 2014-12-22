<?php namespace Arcanedev\Stripe\Utilities\CurlEntities;

class RequestHeaders extends CaseInsensitiveArray
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $container   = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($rawRequestHeaders)
    {
        $this->parseHeaders($rawRequestHeaders);
    }

    private function parseHeaders($rawRequestHeaders)
    {
        $rawRequestHeaders  = preg_split('/\r\n/', $rawRequestHeaders, null, PREG_SPLIT_NO_EMPTY);

        for ($i = 1; $i < count($rawRequestHeaders); $i++) {
            list($key, $value) = explode(':', $rawRequestHeaders[$i], 2);
            $key   = trim($key);
            $value = trim($value);
            // Use isset() as array_key_exists() and ArrayAccess are not compatible.
            if (isset($this[$key])) {
                $this[$key] .= ',' . $value;
                continue;
            }

            $this[$key] = $value;
        }

        $this['Request-Line'] = isset($rawRequestHeaders['0']) ? $rawRequestHeaders['0'] : '';
    }
}
