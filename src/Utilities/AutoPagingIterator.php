<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Collection;
use Iterator;

/**
 * Class     AutoPagingIterator
 *
 * @package  Arcanedev\Stripe\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AutoPagingIterator implements Iterator
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private $lastId = null;

    /** @var \Arcanedev\Stripe\Collection  */
    private $page   = null;

    /** @var int */
    private $pageOffset = 0;

    /** @var array */
    private $params = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * AutoPagingIterator constructor.
     *
     * @param  \Arcanedev\Stripe\Collection  $collection
     * @param  array                         $params
     */
    public function __construct(Collection $collection, array $params)
    {
        $this->page   = $collection;
        $this->params = $params;
    }

    /**
     * Make a AutoPagingIterator object.
     *
     * @param  \Arcanedev\Stripe\Collection  $collection
     * @param  array                         $params
     *
     * @return self
     */
    public static function make(Collection $collection, array $params)
    {
        return new self($collection, $params);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Return the current element.
     * @link  http://php.net/manual/en/iterator.current.php
     *
     * @return mixed
     */
    public function current()
    {
        $item         = current($this->page->data);
        $this->lastId = $item !== false ? $item['id'] : null;

        return $item;
    }

    /**
     * Move forward to next element.
     * @link  http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        $item = next($this->page->data);

        if ($item === false) {
            // If we've run out of data on the current page, try to fetch another one
            // and increase the offset the new page would start at
            $this->pageOffset += count($this->page->data);

            if ($this->page['has_more']) {
                $this->params = array_merge(
                    $this->params ? $this->params : [],
                    ['starting_after' => $this->lastId]
                );

                $this->page   = $this->page->all($this->params);
            }
            else return;
        }
    }

    /**
     * Return the key of the current element.
     * @link  http://php.net/manual/en/iterator.key.php
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->page->data) + $this->pageOffset;
    }

    /**
     * Checks if current position is valid.
     * @link  http://php.net/manual/en/iterator.valid.php
     *
     * @return bool
     */
    public function valid()
    {
        $key  = key($this->page->data);

        return ($key !== null && $key !== false);
    }

    /**
     * Rewind the Iterator to the first element.
     * @link  http://php.net/manual/en/iterator.rewind.php
     */
    public function rewind()
    {
        // Actually rewinding would require making a copy of the original page.
    }
}
