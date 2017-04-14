<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface Payout
 *
 * @package  Arcanedev\Stripe\Contracts\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Payout
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Retrieve a payout.
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * List all the payouts.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null);

    /**
     * Create the payout.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Update the payout.
     *
     * @param  string            $id
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function update($id, $params = null, $options = null);

    /**
     * Cancel the payout.
     *
     * @return self
     */
    public function cancel();

    /**
     * Save the payout.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);
}
