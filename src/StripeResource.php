<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\StripeResource as StripeResourceContract;
use Arcanedev\Stripe\Http\RequestOptions;
use Arcanedev\Stripe\Http\Requestor;
use Arcanedev\Stripe\Utilities\Util;
use ReflectionClass;

/**
 * Class     StripeResource
 *
 * @package  Arcanedev\Stripe
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class StripeResource extends StripeObject implements StripeResourceContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    private static $persistedHeaders = [
        'Stripe-Account' => true,
        'Stripe-Version' => true,
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base url.
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
     * @return self
     */
    public function refresh()
    {
        list($response, $this->opts->apiKey) = Requestor::make($this->opts->apiKey, self::baseUrl())
            ->get($this->instanceUrl(), $this->retrieveParameters);


        /** @var \Arcanedev\Stripe\Http\Response $response */
        $this->setLastResponse($response);
        $this->refreshFrom($response->getJson(), $this->opts);

        return $this;
    }

    /**
     * Get The name of the class, with namespacing and underscores stripped.
     *
     * @param  string  $class
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
     * Get Class short name.
     *
     * @param  string  $class
     *
     * @return string
     */
    protected static function getShortNameClass($class = '')
    {
        if (empty($class))
            $class = get_called_class();

        $class = new ReflectionClass($class);

        return $class->getShortName();
    }

    /**
     * Get the endpoint URL for the given class.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        $base = self::className($class);

        return "/v1/${base}s";
    }

    /**
     * Get Instance URL.
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl()
    {
        return static::resourceUrl($this['id']);
    }

    /**
     * Get the instance endpoint URL for the given class.
     *
     * @param  string  $id
     *
     * @return string
     *
     * @throws Exceptions\InvalidRequestException
     */
    public static function resourceUrl($id)
    {
        if ($id === null) {
            $class = get_called_class();
            throw new Exceptions\InvalidRequestException(
                "Could not determine which URL to request: $class instance has invalid ID: $id", null
            );
        }

        return static::classUrl().'/'.urlencode(str_utf8($id));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a request.
     *
     * @param  string             $method
     * @param  string             $url
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return array
     */
    protected function request($method, $url, $params = [], $options = null)
    {
        $opts = $this->opts->merge($options);

        /** @var \Arcanedev\Stripe\Http\Response $response */
        list($response, $options) = static::staticRequest($method, $url, $params, $opts);
        $this->setLastResponse($response);

        return [$response->getJson(), $options];
    }

    /**
     * Make a request (static).
     *
     * @param  string             $method
     * @param  string             $url
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return array
     */
    protected static function staticRequest($method, $url, $params, $options)
    {
        $opts      = RequestOptions::parse($options);
        $requestor = Requestor::make($opts->apiKey, static::baseUrl());

        list($response, $opts->apiKey) =
            $requestor->request($method, $url, $params, $opts->headers);

        foreach ($opts->headers as $k => $v) {
            if ( ! array_key_exists($k, self::$persistedHeaders))
                unset($opts->headers[$k]);
        }

        return [$response, $opts];
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Scope Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve scope.
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    protected static function scopedRetrieve($id, $options = null)
    {
        $class    = get_called_class();

        /** @var self $resource */
        $resource = new $class($id, RequestOptions::parse($options));
        $resource->refresh();

        return $resource;
    }

    /**
     * List scope.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    protected static function scopedAll($params = [], $options = null)
    {
        self::checkArguments($params, $options);

        $url = static::classUrl();

        /** @var \Arcanedev\Stripe\Http\Response $response */
        list($response, $opts) = self::staticRequest('get', $url, $params, $options);

        $object = Util::convertToStripeObject($response->getJson(), $opts);

        self::checkIsCollectionObject($object);

        $object->setLastResponse($response);
        $object->setRequestParams($params);

        return $object;
    }

    /**
     * Create scope.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     * @throws \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     *
     * @return self
     */
    protected static function scopedCreate($params = [], $options = null)
    {
        self::checkArguments($params, $options);

        /** @var \Arcanedev\Stripe\Http\Response $response */
        list($response, $opts) = static::staticRequest('post', static::classUrl(), $params, $options);

        $object = Util::convertToStripeObject($response->getJson(), $opts);
        $object->setLastResponse($response);

        return $object;
    }

    /**
     * Update scope.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    protected static function scopedUpdate($id, $params = null, $options = null)
    {
        self::checkArguments($params, $options);

        /** @var \Arcanedev\Stripe\Http\Response $response */
        list($response, $opts) = static::staticRequest('post', static::resourceUrl($id), $params, $options);

        $object = Util::convertToStripeObject($response->getJson(), $opts);
        $object->setLastResponse($response);

        return $object;
    }

    /**
     * Save scope.
     *
     * @param  array|string|null  $options
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     *
     * @return self
     */
    protected function scopedSave($options = null)
    {
        if (count($params = $this->serializeParameters()) > 0) {
            self::checkArguments(null, $options);
            list($response, $opts) = $this->request('post', $this->instanceUrl(), $params, $options);
            $this->refreshFrom($response, $opts);
        }

        return $this;
    }

    /**
     * Delete Scope.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     *
     * @return self
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
     * Custom Post Call.
     *
     * @param  string             $url
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    protected function scopedPostCall($url, $params = [], $options = null)
    {
        /** @var \Arcanedev\Stripe\Http\Response $response */
        list($response, $options) = Requestor::make(RequestOptions::parse($options)->getApiKey(), static::baseUrl())
            ->post($url, $params);

        $this->refreshFrom($response->getJson(), $options);
        $this->setLastResponse($response);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check Arguments.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     * @throws \Arcanedev\Stripe\Exceptions\BadMethodCallException
     * @throws \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    protected static function checkArguments($params = [], $options = null)
    {
        self::checkParameters($params);
        self::checkOptions($options);
    }

    /**
     * Check parameters.
     *
     * @param  array|null  $params
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    private static function checkParameters($params)
    {
        if ($params && ! is_array($params)) {
            throw new Exceptions\InvalidArgumentException(
                'You must pass an array as the first argument to Stripe API method calls.  '
                . '(HINT: an example call to create a charge would be: '
                . 'StripeCharge::create([\'amount\' => 100, \'currency\' => \'usd\', '
                . '\'card\' => [\'number\' => 4242424242424242, \'exp_month\' => 5, '
                . '\'exp_year\' => 2015]]))');
        }
    }

    /**
     * Check Options.
     *
     * @param  array|string|null  $options
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkOptions($options)
    {
        if ($options && (
                ! $options instanceof RequestOptions &&
                ! is_string($options) &&
                ! is_array($options)
            )
        ) {
            throw new Exceptions\ApiException(
                'The second argument to Stripe API method calls is an '
                . 'optional per-request apiKey, which must be a string.  '
                . '(HINT: you can set a global apiKey by "Stripe::setApiKey(<apiKey>)")',
                500
            );
        }
    }

    /**
     * Check the object is a Collection class.
     *
     * @param  mixed  $object
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private static function checkIsCollectionObject($object)
    {
        if ( ! is_a($object, Collection::class)) {
            throw new Exceptions\ApiException(
                'Expected type "Arcanedev\Stripe\Collection", got "'.get_class($object).'" instead'
            );
        }
    }
}
