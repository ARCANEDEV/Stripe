<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\ObjectInterface;
use Arcanedev\Stripe\Exceptions\InvalidArgumentException;
use Arcanedev\Stripe\Utilities\Util;
use Arcanedev\Stripe\Utilities\UtilSet;
use ArrayAccess;

/**
 * @property string id
 * @property string object
 */
class Object implements ObjectInterface, ArrayAccess
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    protected $apiKey;

    /** @var array */
    protected $values;

    /** @var UtilSet */
    protected $unsavedValues;

    /** @var UtilSet */
    protected $transientValues;

    /** @var array */
    protected $retrieveOptions;

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

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($id = null, $apiKey = null)
    {
        $this->apiKey          = $apiKey;
        $this->values          = [];
        $this->unsavedValues   = new UtilSet;
        $this->transientValues = new UtilSet;
        $this->retrieveOptions = [];

        if (is_array($id)) {
            foreach ($id as $key => $value) {
                if ($key != 'id') {
                    $this->retrieveOptions[$key] = $value;
                }
            }
            $id = $id['id'];
        }

        if ($id !== null) {
            $this->id = $id;
        }
    }

    /**
     * Init the object
     */
    public static function init()
    {
        self::$permanentAttributes       = new UtilSet(['_apiKey', 'id']);
        self::$nestedUpdatableAttributes = new UtilSet(['metadata']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters (+Magics)
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string|int $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        $class  = get_class($this);

        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        $message    = "Stripe Notice: Undefined property of $class instance: $key.";

        if ($this->transientValues->includes($key)) {
            $attributes = join(', ', array_keys($this->values));
            $message    .= " HINT: The $key attribute was set in the past, however. "
                . "It was then wiped when refreshing the object "
                . "with the result returned by Stripe's API, "
                . "probably as a result of a save(). The attributes currently "
                . "available on this object are: $attributes";
        }

        error_log($message);

        return null;
    }

    /**
     * Standard set accessor
     *
     * @param $key
     * @param $value
     *
     * @throws InvalidArgumentException
     */
    public function __set($key, $value)
    {
        $supportedAttributes = array_keys($this->values);

        $this->checkIfAttributeDeletion($key, $value);

        $this->checkMetadataAttribute($key, $value);

        if (
            self::$nestedUpdatableAttributes->includes($key)
            and isset($this->$key) and is_array($value)
        ) {
            $this->$key->replaceWith($value);
        }
        else {
            // TODO: may want to clear from $_transientValues (Won't be user-visible).
            $this->values[$key] = $value;
        }

        if (! self::$permanentAttributes->includes($key)) {
            $this->unsavedValues->add($key);
        }

        if ($this->checkUnsavedAttributes) {
            $this->checkUnsavedAttributes($supportedAttributes);
        }
    }

    public function __isset($k)
    {
        return isset($this->values[$k]);
    }

    public function __unset($k)
    {
        unset($this->values[$k]);

        $this->transientValues->add($k);
        $this->unsavedValues->discard($k);
    }

    public function __toJSON()
    {
        $array = $this->__toArray(true);

        return defined('JSON_PRETTY_PRINT')
            ? json_encode($array, JSON_PRETTY_PRINT)
            : json_encode($array);
    }

    public function __toString()
    {
        $class = get_class($this);

        return $class . ' JSON: ' . $this->__toJSON();
    }

    public function __toArray($recursive = false)
    {
        return $recursive
            ? Util::convertStripeObjectToArray($this->values)
            : $this->values;
    }

    /**
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
    public function offsetSet($k, $v)
    {
        $this->$k = $v;
    }

    public function offsetExists($k)
    {
        return array_key_exists($k, $this->values);
    }

    public function offsetUnset($k)
    {
        unset($this->$k);
    }

    public function offsetGet($k)
    {
        return array_key_exists($k, $this->values) ? $this->values[$k] : null;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * This unfortunately needs to be public to be used in Util.php
     *
     * @param string      $class
     * @param array       $values
     * @param string|null $apiKey
     *
     * @return Object The object constructed from the given values.
     */
    public static function scopedConstructFrom($class, $values, $apiKey = null)
    {
        /** @var Object $obj $obj */
        $obj = new $class(isset($values['id']) ? $values['id'] : null, $apiKey);
        $obj->refreshFrom($values, $apiKey);

        return $obj;
    }

    /**
     * @param array $values
     * @param string|null $apiKey
     *
     * @return Object The object of the same class as $this constructed
     *    from the given values.
     */
    public static function constructFrom($values, $apiKey = null)
    {
        return self::scopedConstructFrom(__CLASS__, $values, $apiKey);
    }

    /**
     * Refreshes this object using the provided values.
     *
     * @param array   $values
     * @param string  $apiKey
     * @param boolean $partial Defaults to false.
     */
    public function refreshFrom($values, $apiKey, $partial = false)
    {
        $this->apiKey = $apiKey;

        // Wipe old state before setting new.  This is useful for e.g. updating a
        // customer, where there is no persistent card parameter.  Mark those values
        // which don't persist as transient
        $removed = $partial
            ? new UtilSet
            : array_diff(array_keys($this->values), array_keys($values));

        foreach ($removed as $k) {
            if (! self::$permanentAttributes->includes($k)) {
                unset($this->$k);
            }
        }

        foreach ($values as $k => $v) {
            if (self::$permanentAttributes->includes($k) and isset($this[$k])) {
                continue;
            }

            $this->values[$k] = ( self::$nestedUpdatableAttributes->includes($k) and is_array($v) )
                ? Object::scopedConstructFrom('Arcanedev\\Stripe\\AttachedObject', $v, $apiKey)
                : Util::convertToStripeObject($v, $apiKey);

            $this->transientValues->discard($k);
            $this->unsavedValues->discard($k);
        }
    }

    /**
     * A recursive mapping of attributes to values for this object,
     * including the proper value for deleted attributes.
     *
     * @return array
     */
    public function serializeParameters()
    {
        $params = [];

        if ($this->unsavedValues) {
            foreach ($this->unsavedValues->toArray() as $k) {
                $v = $this->$k;

                if (is_null($v)) {
                    $v = '';
                }

                $params[$k] = $v;
            }
        }

        // Get nested updates.
        foreach (self::$nestedUpdatableAttributes->toArray() as $property) {
            if (
                isset($this->$property) and $this->$property instanceof Object
            ) {
                $params[$property] = $this->$property->serializeParameters();
            }
        }

        return $params;
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
     * @param string $class
     * @param string $method
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
     * @param string     $key
     * @param mixed|null $value
     *
     * @throws InvalidArgumentException
     */
    private function checkIfAttributeDeletion($key, $value)
    {
        if ($value === '' and ! is_null($value)) {
            throw new InvalidArgumentException("You cannot set '$key' to an empty string. "
                . "We interpret empty strings as 'null' in requests. "
                . "You may set obj->$key = null to delete the property");
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
            $this->isMetadataAttribute($key)
            and ( ! is_array($value) and ! is_null($value) )
        ) {
            throw new InvalidArgumentException(
                "The metadata value must be an array or null, " . gettype($value) . " is given"
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
     * @param array $supported
     *
     * @throws InvalidArgumentException
     */
    private function checkUnsavedAttributes($supported = [])
    {
        if (count($supported) == 0) {
            return;
        }

        $notFound = array_diff($this->unsavedValues->keys(), $supported);
        if (count($notFound) > 0) {
            throw new InvalidArgumentException('The attributes [' . implode(', ', $notFound) . '] are not supported.');
        }
    }
}
