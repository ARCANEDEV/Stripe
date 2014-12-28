<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\ResourceInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\BadMethodCallException;
use Arcanedev\Stripe\Exceptions\InvalidArgumentException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Utilities\Util;
use ReflectionClass;

abstract class Resource extends Object implements ResourceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base url
     *
     * @return string
     */
    public static function baseUrl()
    {
        return Stripe::getApiBaseUrl();
    }

    /**
     * Get the refreshed resource.
     *
     * @returns Resource
     */
    public function refresh()
    {
        $url    = $this->instanceUrl();

        list($response, $apiKey) = Requestor::make($this->apiKey, self::baseUrl())
            ->get($url, $this->retrieveParameters);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Get The name of the class, with namespacing and underscores stripped.
     *
     * @param string $class
     *
     * @return string
     */
    public static function className($class = '')
    {
        $name = self::getShortNameClass($class);
        $name = str_split_camelcase($name, '_');

        return strtolower(urlencode($name));
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected static function getShortNameClass($class = '')
    {
        if (empty($class)) {
            $class = get_called_class();
        }

        $class = new ReflectionClass($class);

        return $class->getShortName();
    }

    /**
     * Get the endpoint URL for the given class.
     *
     * @param string $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        $base   = self::className($class);

        return "/v1/${base}s";
    }

    /**
     * Get Instance URL
     *
     * @throws InvalidRequestException
     *
     * @return string The full API URL for this API resource.
     */
    public function instanceUrl()
    {
        // TODO: Add end point in instanceUrl() method as arg
        $id     = $this['id'];
        $class  = get_class($this);

        if (is_null($id)) {
            $message = "Could not determine which URL to request: $class instance has invalid ID: $id";

            throw new InvalidRequestException($message, null);
        }

        $id     = str_utf8($id);
        $base   = $this->lsb('classUrl', $class);
        $extn   = urlencode($id);

        return "$base/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Scope Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List Resources
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    protected static function scopedAll($params = [], $apiKey = null)
    {
        self::checkArguments($params, $apiKey);

        $class = get_called_class();
        $base  = self::scopedLsb($class, 'baseUrl');
        $url   = self::scopedLsb($class, 'classUrl', $class);

        list($response, $apiKey) = Requestor::make($apiKey, $base)
            ->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * Retrieve a Resource
     *
     * @param string $id
     * @param string $apiKey
     *
     * @return Resource
     */
    protected static function scopedRetrieve($id, $apiKey = null)
    {
        $class    = get_called_class();
        $resource = new $class($id, $apiKey);

        /** @var self $resource */
        $resource->refresh();

        return $resource;
    }

    /**
     * Create a Resource
     *
     * @param array  $params
     * @param string $apiKey
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     *
     * @return Resource
     */
    protected static function scopedCreate($params = null, $apiKey = null)
    {
        self::checkArguments($params, $apiKey);

        $class = get_called_class();
        $url   = self::scopedLsb($class, 'classUrl', $class);
        $base  = self::scopedLsb($class, 'baseUrl');

        list($response, $apiKey) = Requestor::make($apiKey, $base)
            ->post($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @throws ApiException
     * @throws InvalidArgumentException
     * @throws InvalidRequestException
     *
     * @return Resource
     */
    protected function scopedSave()
    {
        $params = $this->serializeParameters();

        if (count($params) > 0) {
            list($response, $apiKey) = Requestor::make($this->apiKey, self::baseUrl())
                ->post($this->instanceUrl(), $params);

            $this->refreshFrom($response, $apiKey);
        }

        return $this;
    }

    /**
     * @param array|null $params
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     * @throws InvalidRequestException
     *
     * @return Resource
     */
    protected function scopedDelete($params = [])
    {
        self::checkArguments($params);

        list($response, $apiKey) = Requestor::make($this->apiKey, self::baseUrl())
            ->delete($this->instanceUrl(), $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Custom Scope Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Custom Post Call
     *
     * @param string      $url
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Resource
     */
    protected function scopedPostCall($url, $params = [], $apiKey = null)
    {
        if (is_null($apiKey)) {
            $apiKey = $this->apiKey;
        }

        list($response, $apiKey) = Requestor::make($apiKey)
            ->post($url, $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Make a POST Request
     *
     * @param string      $url
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return array
     */
    protected function postRequest($url, $params = [], $apiKey = null)
    {
        return Requestor::make($apiKey)->post($url, $params);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @throws ApiException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    private static function checkArguments($params = [], $apiKey = null)
    {
        self::checkParameters($params);

        self::checkApiKey($apiKey);
    }

    /**
     * Check parameters
     *
     * @param array $params
     *
     * @throws InvalidArgumentException
     */
    private static function checkParameters($params)
    {
        if ($params and ! is_array($params)) {
            $message = "You must pass an array as the first argument to Stripe API "
                . "method calls.  (HINT: an example call to create a charge "
                . "would be: \"StripeCharge::create(array('amount' => 100, "
                . "'currency' => 'usd', 'card' => array('number' => "
                . "4242424242424242, 'exp_month' => 5, 'exp_year' => 2015)))\")";

            throw new InvalidArgumentException($message);
        }
    }

    /**
     * Check API Key
     *
     * @param string $apiKey
     *
     * @throws ApiException
     */
    private static function checkApiKey($apiKey)
    {
        if ($apiKey and ! is_string($apiKey)) {
            $message = 'The second argument to Stripe API method calls is an '
                . 'optional per-request apiKey, which must be a string.  '
                . '(HINT: you can set a global apiKey by "Stripe::setApiKey(<apiKey>)")';

            throw new ApiException($message, 500);
        }
    }
}
