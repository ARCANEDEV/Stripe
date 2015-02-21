<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\RecipientInterface;
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
 * @property Collection     cards
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
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Recipient
     */
    public static function retrieve($id, $options = null)
    {
        return parent::scopedRetrieve($id, $options);
    }

    /**
     * List all Recipients
     * @link https://stripe.com/docs/api/php#list_recipients
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return parent::scopedAll($params, $options);
    }

    /**
     * Create a New Recipient
     * @link https://stripe.com/docs/api/php#create_recipient
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Recipient|array
     */
    public static function create($params = [], $options = null)
    {
        return parent::scopedCreate($params, $options);
    }

    /**
     * Update/Save a recipient
     * @link https://stripe.com/docs/api/php#update_recipient
     *
     * @param  array|string|null $options
     *
     * @return Recipient
     */
    public function save($options = null)
    {
        return parent::scopedSave($options);
    }

    /**
     * Delete a Recipient
     * @link https://stripe.com/docs/api/php#delete_recipient
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Recipient
     */
    public function delete($params = [], $options = null)
    {
        return parent::scopedDelete($params, $options);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all recipient's Transfers.
     *
     * @param  array $params
     *
     * @return Collection|array
     */
    public function transfers($params = [])
    {
        $this->addRecipientParam($params);

        return Transfer::all($params, $this->opts);
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
