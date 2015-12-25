<?php namespace Arcanedev\Stripe\Contracts\Utilities;

/**
 * Interface  Jsonable
 *
 * @package   Arcanedev\Stripe\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Jsonable
{
    /**
     * Convert to json.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0);
}
