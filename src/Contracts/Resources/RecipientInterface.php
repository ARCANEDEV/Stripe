<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;

/**
 * Interface  RecipientInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RecipientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Recipient.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_recipient
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Recipients.
     *
     * @link   https://stripe.com/docs/api/php#list_recipients
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create a New Recipient.
     *
     * @link   https://stripe.com/docs/api/php#create_recipient
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save a recipient.
     *
     * @link   https://stripe.com/docs/api/php#update_recipient
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Delete a Recipient.
     *
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
     * List all recipient's Transfers.
     *
     * @param  array|null $params
     *
     * @return Collection|array
     */
    public function transfers($params = []);
}
