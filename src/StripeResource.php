<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\StripeResourceInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\BadMethodCallException;
use Arcanedev\Stripe\Exceptions\InvalidArgumentException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Utilities\RequestOptions;
use Arcanedev\Stripe\Utilities\Util;
use ReflectionClass;

/**
 * Class StripeResource
 * @package Arcanedev\Stripe
 */
abstract class StripeResource extends StripeObject implements StripeResourceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private static $persistedHeaders = [
        'Stripe-Account' => true,
        'Stripe-Version' => true
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

        list($response, $this->opts->apiKey) = Requestor::make($this->opts->apiKey, self::baseUrl())
            ->get($url, $this->retrieveParameters);

        $this->refreshFrom($response, $this->opts);

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

        $base   = $this->lsb('classUrl', $class);
        $extn   = urlencode(str_utf8($id));

        return "$base/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a request
     *
     * @param string              $method
     * @param string              $url
     * @param array|null          $params
     * @param array|string|null   $options
     *
     * @return array
     */
    protected function request($method, $url, $params = [], $options = null)
    {
        $opts = $this->opts->merge($options);

        return static::staticRequest($method, $url, $params, $opts);
    }

    /**
     * Make a request
     *
     * @param string              $method
     * @param string              $url
     * @param array|null          $params
     * @param array|string|null   $options
     *
     * @return array
     */
    protected static function staticRequest($method, $url, $params, $options)
    {
        $opts = RequestOptions::parse($options);

        list($response, $opts->apiKey) = Requestor::make($opts->apiKey, static::baseUrl())
            ->request($method, $url, $params, $opts->headers);

        foreach ($opts->headers as $k => $v) {
            if ( ! array_key_exists($k, self::$persistedHeaders)) {
                unset($opts->headers[$k]);
            }
        }

        return [$response, $opts];
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Scope Functions
     | ------------------------------------------------------------------------------------------------
     */
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

        /** @var self $resource */
        $resource = new $class($id, $opts);
        $resource->refresh();

        return $resource;
    }

    /**
     * List scope
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    protected static function scopedAll($params = [], $options = null)
    {
        self::checkArguments($params, $options);
        $url = static::classUrl();

        list($response, $opts) = self::staticRequest('get', $url, $params, $options);

        return Util::convertToStripeObject($response, $opts);
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

        $url = static::classUrl();

        list($response, $opts) = self::staticRequest('post', $url, $params, $options);

        return Util::convertToStripeObject($response, $opts);
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
            list($response, $opts) = $this->request('post', $this->instanceUrl(), $params, $options);
            $this->refreshFrom($response, $opts);
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

        list($response, $opts) = $this->request('delete', $this->instanceUrl(), $params, $options);
        $this->refreshFrom($response, $opts);

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
        $opts = RequestOptions::parse($options);

        list($response, $options) = Requestor::make($opts->getApiKey(), static::baseUrl())
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
        if ($params && ! is_array($params)) {
            $message = 'You must pass an array as the first argument to Stripe API method calls.  '
                . '(HINT: an example call to create a charge would be: '
                . 'StripeCharge::create([\'amount\' => 100, \'currency\' => \'usd\', '
                . '\'card\' => [\'number\' => 4242424242424242, \'exp_month\' => 5, '
                . '\'exp_year\' => 2015]]))';

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
        if ($options && (
                ! $options instanceof RequestOptions &&
                ! is_string($options) &&
                ! is_array($options)
            )
        ) {
            $message = 'The second argument to Stripe API method calls is an '
                . 'optional per-request apiKey, which must be a string.  '
                . '(HINT: you can set a global apiKey by "Stripe::setApiKey(<apiKey>)")';

            throw new ApiException($message, 500);
        }
    }
}
