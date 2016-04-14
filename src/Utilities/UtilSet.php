<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Contracts\Utilities\UtilSetInterface;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class     UtilSet
 *
 * @package  Arcanedev\Stripe\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UtilSet implements UtilSetInterface, IteratorAggregate, Countable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    private $items;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($attributes = [])
    {
        $this->items = [];

        foreach ($attributes as $attribute) {
            $this->add($attribute);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if attribute is included.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function includes($attribute)
    {
        return isset($this->items[$attribute]);
    }

    /**
     * Add attribute
     *
     * @param $attribute
     */
    public function add($attribute)
    {
        $this->items[$attribute] = true;
    }

    /**
     * @param $attribute
     */
    public function discard($attribute)
    {
        unset($this->items[$attribute]);
    }

    /**
     * Get all attributes key
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->items);
    }

    /**
     * Diff Keys
     *
     * @param array $keys
     *
     * @return array
     */
    public function diffKeys(array $keys = [])
    {
        return array_diff($this->keys(), $keys);
    }

    /**
     * Get all Attributes
     *
     * @return array
     */
    public function toArray()
    {
        return $this->count() ? $this->keys() : [];
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * Count items
     *
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->items);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the items are empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }
}
