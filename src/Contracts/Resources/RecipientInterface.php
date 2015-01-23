<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\Recipient;

interface RecipientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Recipient
     * @link https://stripe.com/docs/api/php#retrieve_recipient
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Recipient
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Recipients
     * @link https://stripe.com/docs/api/php#list_recipients
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create a New Recipient
     * @link https://stripe.com/docs/api/php#create_recipient
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Recipient
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save a recipient
     * @link https://stripe.com/docs/api/php#update_recipient
     *
     * @return Recipient
     */
    public function save();

    /**
     * Delete a Recipient
     * @link https://stripe.com/docs/api/php#delete_recipient
     *
     * @param  array|null $params
     *
     * @return Recipient
     */
    public function delete($params = []);

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all recipient's Transfers.
     *
     * @param  array|null $params
     *
     * @return ListObject|array
     */
    public function transfers($params = []);
}
