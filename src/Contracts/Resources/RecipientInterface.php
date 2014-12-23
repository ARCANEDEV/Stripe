<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Recipient object Interface
 * @link https://stripe.com/docs/api/php#recipient_object
 *
 * @property string                  id
 * @property string                  object      // "recipient"
 * @property bool                    livemode
 * @property int                     created
 * @property string                  type
 * @property Object                  active_account
 * @property string                  description
 * @property string                  email
 * @property AttachedObjectInterface metadata
 * @property string                  name
 * @property ListObjectInterface     cards
 * @property string                  default_card
 */
interface RecipientInterface
{
    /**
     * Retrieve a Recipient
     * @link https://stripe.com/docs/api/php#retrieve_recipient
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return RecipientInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * List all Recipients
     * @link https://stripe.com/docs/api/php#list_recipients
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $apiKey = null);

    /**
     * Create a New Recipient
     * @link https://stripe.com/docs/api/php#create_recipient
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return RecipientInterface
     */
    public static function create($params = [], $apiKey = null);

    /**
     * Update/Save a recipient
     * @link https://stripe.com/docs/api/php#update_recipient
     *
     * @return RecipientInterface
     */
    public function save();

    /**
     * Delete a Recipient
     * @link https://stripe.com/docs/api/php#delete_recipient
     *
     * @param array $params
     *
     * @return RecipientInterface
     */
    public function delete($params = []);

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all recipient's Transfers.
     *
     * @param array $params
     *
     * @return ListObjectInterface
     */
    public function transfers($params = []);
}
