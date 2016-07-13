<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\RecipientInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Recipient
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#recipient_object
 *
 * @deprecated since the end of 2015, use Connect (https://stripe.com/docs/connect) instead.
 *
 * @property  string                                        id
 * @property  string                                        object          // 'recipient'
 * @property  \Arcanedev\Stripe\Bases\ExternalAccount|null  active_account
 * @property  \Arcanedev\Stripe\Collection                  cards
 * @property  int                                           created         // timestamp
 * @property  string                                        default_card
 * @property  string                                        description
 * @property  string                                        email
 * @property  bool                                          livemode
 * @property  \Arcanedev\Stripe\AttachedObject              metadata
 * @property  string|null                                   migrated_to
 * @property  string                                        name
 * @property  string                                        type            // 'individual' or 'corporation'
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
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Recipient.
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
     * Create a new Recipient.
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
     * Update a Recipient.
     * @link   https://stripe.com/docs/api/php#update_recipient
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Update/Save a Recipient.
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
     * List all Recipient's Transfers.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
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
