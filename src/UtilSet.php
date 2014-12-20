<?php namespace Arcanedev\Stripe;

use ArrayIterator;
use IteratorAggregate;

class UtilSet implements IteratorAggregate
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
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function includes($attribute)
    {
        return isset($this->items[$attribute]);
    }

    /**
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
     * Get all Attributes
     *
     * @return array
     */
    public function toArray()
    {
        return $this->keys();
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }
}
