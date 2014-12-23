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
    const METHOD_ALL    = 'all';
    const METHOD_CREATE = 'create';
    const METHOD_SAVE   = 'save';
    const METHOD_DELETE = 'delete';

    /** @var array */
    private static $allowedMethods = [
        self::METHOD_ALL, self::METHOD_CREATE, self::METHOD_SAVE, self::METHOD_DELETE
    ];

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
        return Stripe::$apiBase;
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
            ->get($url, $this->retrieveOptions);

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
        $name = splitCamelCase($name, '_');

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
     * @throws InvalidRequestException
     *
     * @return string The full API URL for this API resource.
     */
    public function instanceUrl()
    {
        $id     = $this['id'];
        $class  = get_class($this);

        if (is_null($id)) {
            $message = "Could not determine which URL to request: $class instance has invalid ID: $id";

            throw new InvalidRequestException($message, null);
        }

        $id     = Requestor::utf8($id);
        $base   = $this->lsb('classUrl', $class);
        $extn   = urlencode($id);

        return "$base/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  Scope Functions
     | ------------------------------------------------------------------------------------------------
     */
    protected static function scopedAll($class, $params = null, $apiKey = null)
    {
        self::validateCall('all', $params, $apiKey);

        $base = self::scopedLsb($class, 'baseUrl');
        $url  = self::scopedLsb($class, 'classUrl', $class);

        list($response, $apiKey) = Requestor::make($apiKey, $base)
            ->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @param string $class
     * @param string $id
     * @param string $apiKey
     *
     * @return Resource
     */
    protected static function scopedRetrieve($class, $id, $apiKey = null)
    {
        /** @var self $instance */
        $instance = new $class($id, $apiKey);
        $instance->refresh();

        return $instance;
    }

    /**
     * @param string $class
     * @param array  $params
     * @param string $apiKey
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     *
     * @return Object|array
     */
    protected static function scopedCreate($class, $params = null, $apiKey = null)
    {
        self::validateCall('create', $params, $apiKey);

        $url  = self::scopedLsb($class, 'classUrl', $class);
        $base = self::scopedLsb($class, 'baseUrl');
        list($response, $apiKey) = Requestor::make($apiKey, $base)
            ->post($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @param string $class
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     * @throws InvalidRequestException
     *
     * @return Resource
     */
    protected function scopedSave($class)
    {
        // TODO: Remove unused $class arg from scopedSave($class)
        self::validateCall('save');

        $params = $this->serializeParameters();

        if (count($params) > 0) {
            list($response, $apiKey) = Requestor::make($this->apiKey, self::baseUrl())
                ->post($this->instanceUrl(), $params);

            $this->refreshFrom($response, $apiKey);
        }

        return $this;
    }

    /**
     * @param string $class
     * @param array|null $params
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     * @throws InvalidRequestException
     *
     * @return Resource
     */
    protected function scopedDelete($class, $params = null)
    {
        // TODO: Remove unused $class arg from scopedDelete($class)
        self::validateCall('delete');

        list($response, $apiKey) = Requestor::make($this->apiKey, self::baseUrl())
            ->delete($this->instanceUrl(), $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $method
     * @param null $params
     * @param string|null $apiKey
     *
     * @throws ApiException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    private static function validateCall($method, $params = null, $apiKey = null)
    {
        self::checkMethodCall($method);

        self::checkParameters($params);

        self::checkApiKey($apiKey);
    }

    /**
     * Check Method is allowed
     *
     * @param string $method
     *
     * @throws BadMethodCallException
     */
    private static function checkMethodCall($method)
    {
        if (! in_array($method, self::$allowedMethods)) {
            $methods = implode(', ', self::$allowedMethods);

            throw new BadMethodCallException(
                "The available methods are [$methods], $method is called !",
                501
            );
        }
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

            throw new ApiException($message);
        }
    }
}
