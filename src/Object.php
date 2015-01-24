<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\ObjectInterface;
use Arcanedev\Stripe\Contracts\Utilities\Arrayable;
use Arcanedev\Stripe\Contracts\Utilities\Jsonable;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\InvalidArgumentException;
use Arcanedev\Stripe\Utilities\Util;
use Arcanedev\Stripe\Utilities\UtilSet;
use ArrayAccess;

/**
 * @property string id
 * @property string object
 */
class Object implements ObjectInterface, ArrayAccess, Arrayable, Jsonable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    protected $apiKey;

    /** @var array */
    protected $values;

    /**
     * Unsaved Values
     *
     * @var UtilSet
     */
    protected $unsavedValues;

    /**
     * Transient (Deleted) Values
     *
     * @var UtilSet
     */
    protected $transientValues;

    /**
     * Retrieve parameters used to query the object
     *
     * @var array
     */
    protected $retrieveParameters;

    /**
     * Attributes that should not be sent to the API because
     * they're not updatable (e.g. API key, ID).
     *
     * @var UtilSet
     */
    public static $permanentAttributes;

    /**
     * Attributes that are nested but still updatable from the
     * parent class's URL (e.g. metadata).
     *
     * @var UtilSet
     */
    public static $nestedUpdatableAttributes;

    /**
     * Allow to check attributes while setting
     *
     * @var bool
     */
    protected $checkUnsavedAttributes = false;

    const ATTACHED_OBJECT_CLASS       = 'Arcanedev\\Stripe\\AttachedObject';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor
     *
     * @param string|array|null $id
     * @param string|null       $apiKey
     */
    public function __construct($id = null, $apiKey = null)
    {
        $this->init();
        $this->setApiKey($apiKey);
        $this->setId($id);
    }

    /**
     * Init Object Properties
     */
    private function init()
    {
        $this->values                    = [];
        self::$permanentAttributes       = new UtilSet(['apiKey', 'id']);
        self::$nestedUpdatableAttributes = new UtilSet(['metadata']);
        $this->unsavedValues             = new UtilSet;
        $this->transientValues           = new UtilSet;
        $this->retrieveParameters        = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters (+Magics)
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get API Key
     *
     * @return string
     */
    protected function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set API Key
     *
     * @param string $apiKey
     *
     * @return Object
     */
    protected function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set Id
     *
     * @param array|string|null $id
     *
     * @throws ApiException
     *
     * @return Object
     */
    private function setId($id)
    {
        if (is_array($id)) {
            $this->setIdFromArray($id);
        }

        if (! is_null($id)) {
            $this->id = $id;
        }

        return $this;
    }

    /**
     * Set Id from array
     *
     * @param array $id
     */
    private function setIdFromArray(&$id)
    {
        $this->checkIdIsInArray($id);

        $this->retrieveParameters = array_diff_key($id, array_flip(['id']));

        $id = $id['id'];
    }

    /**
     * Get Retrieve Parameters
     *
     * @return array
     */
    protected function getRetrieveParams()
    {
        return $this->retrieveParameters;
    }

    /**
     * Standard get accessor
     *
     * @param string|int $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        $this->showUndefinedPropertyMsg(get_class($this), $key);

        return null;
    }

    /**
     * Standard set accessor
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @throws InvalidArgumentException
     */
    public function __set($key, $value)
    {
        $supportedAttributes = array_keys($this->values);

        $this->setValue($key, $value);

        $this->checkUnsavedAttributes($supportedAttributes);
    }

    /**
     * Set value
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws InvalidArgumentException
     */
    private function setValue($key, $value)
    {
        $this->checkIfAttributeDeletion($key, $value);
        $this->checkMetadataAttribute($key, $value);

        if (
            self::$nestedUpdatableAttributes->includes($key) and
            isset($this->$key) and
            is_array($value)
        ) {
            $this->$key->replaceWith($value);
        }
        else {
            // TODO: may want to clear from $transientValues (Won't be user-visible).
            $this->values[$key] = $value;
        }

        $this->checkPermanentAttributes($key);
    }

    /**
     * Check has a value by key
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->values[$key]);
    }

    /**
     * Unset element from values
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->values[$key]);

        $this->transientValues->add($key);
        $this->unsavedValues->discard($key);
    }

    /**
     * Convert Object to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Convert Object to string
     *
     * @return string
     */
    public function toString()
    {
        return get_class($this) . ' JSON: ' . $this->toJson();
    }

    /**
     * Convert Object to array
     *
     * @param bool $recursive
     *
     * @return array
     */
    public function toArray($recursive = false)
    {
        return $recursive
            ? Util::convertStripeObjectToArray($this->values)
            : $this->values;
    }

    /**
     * Convert Object to JSON
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        $array = $this->toArray(true);

        return defined('JSON_PRETTY_PRINT')
            ? json_encode($array, JSON_PRETTY_PRINT)
            : json_encode($array, $options);
    }

    /**
     * Get only value keys
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->values);
    }

    /* ------------------------------------------------------------------------------------------------
     |  ArrayAccess methods
     | ------------------------------------------------------------------------------------------------
     */
    public function offsetSet($key, $value)
    {
        $this->$key = $value;
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->values);
    }

    public function offsetUnset($key)
    {
        unset($this->$key);
    }

    public function offsetGet($key)
    {
        return array_key_exists($key, $this->values)
            ? $this->values[$key]
            : null;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * This unfortunately needs to be public to be used in Util.php
     * Return The object constructed from the given values.
     *
     * @param  string      $class
     * @param  array       $values
     * @param  string|null $apiKey
     *
     * @return self
     */
    public static function scopedConstructFrom($class, $values, $apiKey = null)
    {
        /** @var Object $obj */
        $obj = new $class(isset($values['id']) ? $values['id'] : null, $apiKey);
        $obj->refreshFrom($values, $apiKey);

        return $obj;
    }

    /**
     * Refreshes this object using the provided values.
     *
     * @param array   $values
     * @param string  $apiKey
     * @param boolean $partial
     */
    public function refreshFrom($values, $apiKey, $partial = false)
    {
        $this->apiKey = $apiKey;

        $this->cleanObject($values, $partial);

        foreach ($values as $key => $value) {
            if (self::$permanentAttributes->includes($key) and isset($this[$key])) {
                continue;
            }

            $this->values[$key] = $this->constructValue($key, $value, $apiKey);

            $this->transientValues->discard($key);
            $this->unsavedValues->discard($key);
        }
    }

    /**
     * Clean refreshed Object
     *
     * @param array   $values
     * @param boolean $partial
     */
    private function cleanObject($values, $partial)
    {
        // Wipe old state before setting new.
        // This is useful for e.g. updating a customer, where there is no persistent card parameter.
        // Mark those values which don't persist as transient
        $removed = $partial
            ? new UtilSet
            : array_diff(array_keys($this->values), array_keys($values));

        foreach ($removed as $key) {
            if (! self::$permanentAttributes->includes($key)) {
                unset($this->$key);
            }
        }
    }

    /**
     * Construct Value
     *
     * @param  string $key
     * @param  mixed  $value
     * @param  string $apiKey
     *
     * @return Object|Resource|ListObject|array
     */
    private function constructValue($key, $value, $apiKey)
    {
        return (self::$nestedUpdatableAttributes->includes($key) and is_array($value))
            ? self::scopedConstructFrom(self::ATTACHED_OBJECT_CLASS, $value, $apiKey)
            : Util::convertToStripeObject($value, $apiKey);
    }

    /**
     * Pretend to have late static bindings, even in PHP 5.2
     *
     * @param string $method
     *
     * @return mixed
     */
    protected function lsb($method)
    {
        $class  = get_class($this);
        $args   = array_slice(func_get_args(), 1);

        return call_user_func_array([$class, $method], $args);
    }

    /**
     * Scoped Late Static Bindings
     *
     * @param  string $class
     * @param  string $method
     *
     * @return mixed
     */
    protected static function scopedLsb($class, $method)
    {
        $args = array_slice(func_get_args(), 2);

        return call_user_func_array([$class, $method], $args);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if array has id
     *
     * @param array $array
     *
     * @throws ApiException
     */
    private function checkIdIsInArray($array)
    {
        if (! isset($array['id'])) {
            throw new ApiException("The attribute id must be included.", 500);
        }
    }

    /**
     * Check if object has retrieve parameters
     *
     * @return bool
     */
    public function hasRetrieveParams()
    {
        return $this->retrieveParamsCount() > 0;
    }

    /**
     * @param string     $key
     * @param mixed|null $value
     *
     * @throws InvalidArgumentException
     */
    private function checkIfAttributeDeletion($key, $value)
    {
        if (! is_null($value) and $value === '') {
            $msg = "You cannot set '$key' to an empty string. "
                . 'We interpret empty strings as \'null\' in requests. '
                . "You may set obj->$key = null to delete the property";

            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * @param string     $key
     * @param mixed|null $value
     *
     * @throws InvalidArgumentException
     */
    private function checkMetadataAttribute($key, $value)
    {
        if (
            $this->isMetadataAttribute($key) and
            (! is_array($value) and ! is_null($value))
        ) {
            throw new InvalidArgumentException(
                'The metadata value must be an array or null, ' . gettype($value) . ' is given'
            );
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function isMetadataAttribute($key)
    {
        return $key === "metadata";
    }

    /**
     * Check permanent attributes
     *
     * @param string $key
     */
    private function checkPermanentAttributes($key)
    {
        if (! self::$permanentAttributes->includes($key)) {
            $this->unsavedValues->add($key);
        }
    }

    /**
     * Check unsaved attributes
     *
     * @param  array $supported
     *
     * @throws InvalidArgumentException
     */
    private function checkUnsavedAttributes($supported)
    {
        if (
            ($this->checkUnsavedAttributes == false or count($supported) == 0)
        ) {
            return;
        }

        if (count($notFound = array_diff($this->unsavedValues->keys(), $supported))) {
            throw new InvalidArgumentException(
                'The attributes [' . implode(', ', $notFound) . '] are not supported.'
            );
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve Parameters count
     *
     * @return int
     */
    private function retrieveParamsCount()
    {
        return count($this->getRetrieveParams());
    }

    /**
     * A recursive mapping of attributes to values for this object,
     * including the proper value for deleted attributes.
     *
     * @return array
     */
    protected function serializeParameters()
    {
        return array_merge(
            $this->serializeUnsavedValues(),
            $this->serializeNestedUpdatableAttributes()
        );
    }

    /**
     * Serialize unsaved values
     *
     * @return array
     */
    private function serializeUnsavedValues()
    {
        $params = [];

        foreach ($this->unsavedValues->toArray() as $key) {
            $params[$key] = is_null($value = $this->$key) ? '' : $value;
        }

        return $params;
    }

    /**
     * Serialize nested updatable attributes
     *
     * @return array
     */
    private function serializeNestedUpdatableAttributes()
    {
        $params = [];

        // Get nested updates.
        foreach (self::$nestedUpdatableAttributes->toArray() as $property) {
            if (
                isset($this->$property) and
                $this->$property instanceof self
            ) {
                $params[$property] = $this->$property->serializeParameters();
            }
        }

        return $params;
    }

    /**
     * Show undefined property warning message
     *
     * @param string $class
     * @param string $key
     */
    private function showUndefinedPropertyMsg($class, $key)
    {
        $message = "Stripe Notice: Undefined property of $class instance: $key.";

        if ($this->transientValues->includes($key)) {
            $message .= ' HINT: The [' . $key . '] attribute was set in the past, however. ' .
                'It was then wiped when refreshing the object with the result returned by Stripe\'s API, ' .
                'probably as a result of a save().' .
                $this->showUndefinedPropertyMsgAttributes();
        }

        error_log($message);
    }

    /**
     * Show  available attributes for undefined property warning message
     *
     * @return string
     */
    private function showUndefinedPropertyMsgAttributes()
    {
        return count($attributes = array_keys($this->values)) > 0
            ? ' The attributes currently available on this object are: ' . join(', ', $attributes)
            : '';
    }
}
