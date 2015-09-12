<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\RecipientInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Recipient
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#recipient_object
 *
 * @property  string          id
 * @property  string          object            // "recipient"
 * @property  bool            livemode
 * @property  int             created
 * @property  string          type
 * @property  Object          active_account
 * @property  string          description
 * @property  string          email
 * @property  AttachedObject  metadata
 * @property  string          name
 * @property  Collection      cards
 * @property  string          default_card
 *
 * @todo:     Update the properties.
 */
class Recipient extends StripeResource implements RecipientInterface
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
     * Retrieve a Recipient.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_recipient
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

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
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

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
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update/Save a recipient.
     *
     * @link   https://stripe.com/docs/api/php#update_recipient
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }

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
    public function delete($params = [], $options = null)
    {
        return self::scopedDelete($params, $options);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all recipient's Transfers.
     *
     * @param  array  $params
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
     * Add Recipient ID to Parameters.
     *
     * @param  array  $params
     */
    private function addRecipientParam(&$params)
    {
        $params['recipient'] = $this->id;
    }
}
