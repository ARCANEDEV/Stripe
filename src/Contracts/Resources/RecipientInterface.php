<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  RecipientInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RecipientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Recipients.
     * @link   https://stripe.com/docs/api/php#list_recipients
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Recipient.
     * @link   https://stripe.com/docs/api/php#retrieve_recipient
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a new Recipient.
     * @link   https://stripe.com/docs/api/php#create_recipient
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update a Recipient.
     * @link   https://stripe.com/docs/api/php#update_recipient
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

    /**
     * Update/Save a Recipient.
     * @link   https://stripe.com/docs/api/php#update_recipient
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Delete a Recipient.
     * @link   https://stripe.com/docs/api/php#delete_recipient
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null);

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Recipient's Transfers.
     *
     * @param  array|null  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public function transfers($params = []);
}
