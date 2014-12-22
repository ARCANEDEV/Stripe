<?php namespace Arcanedev\Stripe\Utilities\CurlEntities;

use ArrayAccess;
use Countable;
use Iterator;

class CaseInsensitiveArray implements ArrayAccess, Countable, Iterator
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $container = [];

    /* ------------------------------------------------------------------------------------------------
     |  ArrayAccess Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->container[] = $value;
        } else {
            $index = array_search(strtolower($offset), array_keys(array_change_key_case($this->container, CASE_LOWER)));
            if (!($index === false)) {
                $keys = array_keys($this->container);
                unset($this->container[$keys[$index]]);
            }
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists(strtolower($offset), array_change_key_case($this->container, CASE_LOWER));
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        $index = array_search(strtolower($offset), array_keys(array_change_key_case($this->container, CASE_LOWER)));
        if ($index === false) {
            return null;
        }

        $values = array_values($this->container);
        return $values[$index];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Countable Function
     | ------------------------------------------------------------------------------------------------
     */
    public function count()
    {
        return count($this->container);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Iterator Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function current()
    {
        return current($this->container);
    }

    public function next()
    {
        return next($this->container);
    }

    public function key()
    {
        return key($this->container);
    }

    public function valid()
    {
        return !($this->current() === false);
    }

    public function rewind()
    {
        reset($this->container);
    }
}
