<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\ResourceInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\BadMethodCallException;
use Arcanedev\Stripe\Exceptions\InvalidArgumentException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Utilities\RequestOptions;
use Arcanedev\Stripe\Utilities\Util;
use ReflectionClass;

abstract class Resource extends Object implements ResourceInterface
{
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
     * @return Resource
     */
    public function refresh()
    {
        $url    = $this->instanceUrl();

        list($response, $apiKey) = Requestor::make($this->getApiKey(), self::baseUrl())
            ->get($url, $this->retrieveParameters);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Get The name of the class, with namespacing and underscores stripped.
     *
     * @param  string $class
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
     * Get Class short name
     *
     * @param  string $class
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
     * @param  string $class
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
     * List scope
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject|array
     */
    protected static function scopedAll($params = [], $options = null)
    {
        self::checkArguments($params, $options);

        $class = get_called_class();
        $base  = self::scopedLsb($class, 'baseUrl');
        $url   = self::scopedLsb($class, 'classUrl', $class);
        $opts  = RequestOptions::parse($options);

        list($response, $apiKey) = Requestor::make($opts->getApiKey(), $base)
            ->get($url, $params, $opts->getHeaders());

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * Retrieve scope
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Resource
     */
    protected static function scopedRetrieve($id, $options = null)
    {
        $opts     = RequestOptions::parse($options);
        $class    = get_called_class();
        $resource = new $class($id, $opts->getApiKey());

        /** @var self $resource */
        $resource->refresh();

        return $resource;
    }

    /**
     * Create scope
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     *
     * @return Resource
     */
    protected static function scopedCreate($params = [], $options = null)
    {
        self::checkArguments($params, $options);

        $class = get_called_class();
        $url   = parent::scopedLsb($class, 'classUrl', $class);
        $base  = parent::scopedLsb($class, 'baseUrl');
        $opts  = RequestOptions::parse($options);

        list($response, $apiKey) = Requestor::make($opts->getApiKey(), $base)
            ->post($url, $params, $opts->getHeaders());

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * Save scope
     *
     * @param  array|string|null $options
     *
     * @throws InvalidRequestException
     *
     * @return Resource
     */
    protected function scopedSave($options = null)
    {
        $params = $this->serializeParameters();

        if (count($params) > 0) {
            self::checkArguments(null, $options);
            $opts = $this->parseOptions($options);

            list($response, $apiKey) = Requestor::make($opts->getApiKey(), self::baseUrl())
                ->post($this->instanceUrl(), $params);

            $this->refreshFrom($response, $apiKey);
        }

        return $this;
    }

    /**
     * Delete Scope
     *
     * @param  array|null $params
     * @param  array|string|null $options
     *
     * @throws InvalidRequestException
     *
     * @return Resource
     */
    protected function scopedDelete($params = [], $options = null)
    {
        self::checkArguments($params, $options);
        $opts = $this->parseOptions($options);

        list($response, $apiKey) = Requestor::make($opts->getApiKey(), self::baseUrl())
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
     * @param  string            $url
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Resource
     */
    protected function scopedPostCall($url, $params = [], $options = null)
    {
        $opts = $this->parseOptions($options);

        list($response, $options) = Requestor::make($opts->getApiKey())
            ->post($url, $params);

        $this->refreshFrom($response, $options);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check Arguments
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    private static function checkArguments($params = [], $options = null)
    {
        self::checkParameters($params);

        self::checkOptions($options);
    }

    /**
     * Check parameters
     *
     * @param  array|null $params
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
     * Check Options
     *
     * @param  array|string|null $options
     *
     * @throws ApiException
     */
    private static function checkOptions($options)
    {
        if ($options and (! is_string($options) and ! is_array($options))) {
            $message = 'The second argument to Stripe API method calls is an '
                . 'optional per-request apiKey, which must be a string.  '
                . '(HINT: you can set a global apiKey by "Stripe::setApiKey(<apiKey>)")';

            throw new ApiException($message, 500);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * with either passed in or saved API key
     *
     * @param  array|string|null $options
     *
     * @return RequestOptions
     */
    protected function parseOptions($options)
    {
        $opts   = RequestOptions::parse($options);
        $apiKey = $opts->apiKey ?: $this->getApiKey();
        $opts->setApiKey($apiKey);

        return $opts;
    }
}
