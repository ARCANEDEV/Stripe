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
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
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
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
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
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * Update/Save a recipient
     * @link https://stripe.com/docs/api/php#update_recipient
     *
     * @return Recipient
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
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
        $class = get_class();

        return self::scopedDelete($class, $params);
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
        self::prepareParameters($params);

        $params['recipient'] = $this->id;

        return Transfer::all($params, $this->apiKey);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param array $params
     */
    protected static function prepareParameters(&$params)
    {
        // TODO: Move this method to parent
        if (is_null($params)) {
            $params = [];
        }
    }
}
