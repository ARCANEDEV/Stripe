<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  DisputeInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface DisputeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all Disputes.
     * @link   https://stripe.com/docs/api/php#list_disputes
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Get a Dispute by id.
     * @link   https://stripe.com/docs/api/php#retrieve_dispute
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Update a Dispute.
     * @link   https://stripe.com/docs/api/php#update_dispute
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

    /**
     * Save a Dispute.
     * @link   https://stripe.com/docs/api/php#update_dispute
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Close a Dispute.
     * @link   https://stripe.com/docs/api/php#close_dispute
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function close($options = null);
}
