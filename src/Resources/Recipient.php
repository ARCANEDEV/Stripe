<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\RecipientInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resource;

/**
 * The recipient object
 * @link https://stripe.com/docs/api/php#recipient_object
 *
 * @property string         id
 * @property string         object      // "recipient"
 * @property bool           livemode
 * @property int            created
 * @property string         type
 * @property Object         active_account
 * @property string         description
 * @property string         email
 * @property AttachedObject metadata
 * @property string         name
 * @property ListObject     cards
 * @property string         default_card
 */
class Recipient extends Resource implements RecipientInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Allow to check attributes while setting
     *
     * @var bool
     */
    protected $checkUnsavedAttributes = true;

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Recipient
     * @link https://stripe.com/docs/api/php#retrieve_recipient
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Recipient
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve(get_class(), $id, $apiKey);
    }

    /**
     * List all Recipients
     * @link https://stripe.com/docs/api/php#list_recipients
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        return self::scopedAll(get_class(), $params, $apiKey);
    }

    /**
     * Create a New Recipient
     * @link https://stripe.com/docs/api/php#create_recipient
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Recipient
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate(get_class(), $params, $apiKey);
    }

    /**
     * Update/Save a recipient
     * @link https://stripe.com/docs/api/php#update_recipient
     *
     * @return Recipient
     */
    public function save()
    {
        return self::scopedSave(get_class());
    }

    /**
     * Delete a Recipient
     * @link https://stripe.com/docs/api/php#delete_recipient
     *
     * @param array $params
     *
     * @return Recipient
     */
    public function delete($params = [])
    {
        return self::scopedDelete(get_class(), $params);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all recipient's Transfers.
     *
     * @param array $params
     *
     * @return ListObject
     */
    public function transfers($params = [])
    {
        $this->addRecipientParam($params);

        return Transfer::all($params, $this->apiKey);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add Recipient ID to Parameters
     *
     * @param array $params
     */
    private function addRecipientParam(&$params)
    {
        $params['recipient'] = $this->id;
    }
}
