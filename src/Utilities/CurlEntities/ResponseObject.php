<?php namespace Arcanedev\Stripe\Utilities\CurlEntities;

use Arcanedev\Stripe\Contracts\Utilities\Arrayable;

class ResponseObject implements Arrayable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private $rawResult;

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function isEmpty()
    {
        return empty($this->rawResult);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Convert object to Array
     *
     * @return mixed
     */
    public function toArray()
    {
        return ! $this->isEmpty()
            ? json_decode($this->rawResult)
            : [];
    }
}
