<?php namespace Arcanedev\Stripe;

use ReflectionClass;

use Arcanedev\Stripe\Exceptions\InvalidRequestErrorException;
use Arcanedev\Stripe\Exceptions\StripeError;

abstract class Resource extends Object
{
    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the refreshed resource.
     *
     * @returns Resource
     */
    public function refresh()
    {
        $requestor  = new Requestor($this->apiKey);
        $url        = $this->instanceUrl();

        list($response, $apiKey) = $requestor->get($url, $this->retrieveOptions);

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
        $className = self::getShortNameClass($class);

        return strtolower(urlencode($className));
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private static function getShortNameClass($class = '')
    {
        if ( empty($class) ) {
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
     * @throws InvalidRequestErrorException
     *
     * @return string The full API URL for this API resource.
     */
    public function instanceUrl()
    {
        $id     = $this['id'];
        $class  = get_class($this);

        if ( $id === null ) {
            $message = "Could not determine which URL to request: $class instance has invalid ID: $id";

            throw new InvalidRequestErrorException($message, null);
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

        $requestor  = new Requestor($apiKey);
        $url        = self::scopedLsb($class, 'classUrl', $class);

        list($response, $apiKey) = $requestor->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @param string $class
     * @param string $id
     * @param string $apiKey
     *
     * @return Resource
     */
    protected static function scopedRetrieve($class, $id, $apiKey=null)
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
     * @throws StripeError
     *
     * @return array|Object
     */
    protected static function scopedCreate($class, $params=null, $apiKey=null)
    {
        self::validateCall('create', $params, $apiKey);

        $requestor  = new Requestor($apiKey);
        $url        = self::scopedLsb($class, 'classUrl', $class);

        list($response, $apiKey) = $requestor->post($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @param string $class
     *
     * @throws InvalidRequestErrorException
     * @throws StripeError
     *
     * @return Resource
     */
    // TODO: Remove unused $class arg from scopedSave($class)
    protected function scopedSave($class)
    {
        self::validateCall('save');
        $requestor  = new Requestor($this->apiKey);
        $params     = $this->serializeParameters();

        if (count($params) > 0) {
            list($response, $apiKey) = $requestor->post($this->instanceUrl(), $params);

            $this->refreshFrom($response, $apiKey);
        }

        return $this;
    }

    /**
     * @param string     $class
     * @param array|null $params
     *
     * @throws InvalidRequestErrorException
     * @throws StripeError
     *
     * @return Resource
     */
    // TODO: Remove unused $class arg from scopedDelete($class)
    protected function scopedDelete($class, $params = null)
    {
        self::validateCall('delete');

        $requestor               = new Requestor($this->apiKey);
        list($response, $apiKey) = $requestor->delete($this->instanceUrl(), $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    private static function validateCall($method, $params = null, $apiKey = null)
    {
        if ( ! in_array($method, ['all', 'create', 'save', 'delete'])) {
            // TODO: To Throw or not To Throw ??
        }

        if ($params and ! is_array($params)) {
            $message = "You must pass an array as the first argument to Stripe API "
                . "method calls.  (HINT: an example call to create a charge "
                . "would be: \"StripeCharge::create(array('amount' => 100, "
                . "'currency' => 'usd', 'card' => array('number' => "
                . "4242424242424242, 'exp_month' => 5, 'exp_year' => 2015)))\")";

            throw new StripeError($message);
        }

        if ($apiKey and ! is_string($apiKey)) {
            $message = 'The second argument to Stripe API method calls is an '
                . 'optional per-request apiKey, which must be a string.  '
                . '(HINT: you can set a global apiKey by '
                . '"Stripe::setApiKey(<apiKey>)")';

            throw new StripeError($message);
        }
    }
}
