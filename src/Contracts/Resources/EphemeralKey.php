<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface     EphemeralKey
 *
 * @package  Arcanedev\Stripe\Contracts\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface EphemeralKey
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create the Ephemeral Key.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return self
     */
    public static function create($params = [], $options = null);

    /**
     * Delete the Ephemeral Key.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null);
}
