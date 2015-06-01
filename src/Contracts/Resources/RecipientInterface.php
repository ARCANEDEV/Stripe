<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Recipient;
use Arcanedev\Stripe\Resources\Transfer;

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
     * @return Collection|Recipient[]
     */
    public static function all($params = [], $options = null);

    /**
     * Create a New Recipient
     * @link https://stripe.com/docs/api/php#create_recipient
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Recipient|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save a recipient
     * @link https://stripe.com/docs/api/php#update_recipient
     *
     * @param  array|string|null $options
     *
     * @return Recipient
     */
    public function save($options = null);

    /**
     * Delete a Recipient
     * @link https://stripe.com/docs/api/php#delete_recipient
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Recipient
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
     * @return Collection|Transfer[]
     */
    public function transfers($params = []);
}
