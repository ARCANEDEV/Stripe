<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\RecipientInterface;
use Arcanedev\Stripe\Resource;

/**
 * @property string     id
 * @property mixed|null deleted
 * @property string     email
 * @property mixed|null cards
 * @property mixed|null metadata
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
     * @param string      $id     The ID of the recipient to retrieve.
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
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Recipients.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return Recipient The created recipient.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * @return Recipient The saved recipient.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * @param array|null $params
     *
     * @return Recipient The deleted recipient.
     */
    public function delete($params = null)
    {
        $class = get_class();

        return self::scopedDelete($class, $params);
    }

    /**
     * Get an array of the recipient's Transfers.
     *
     * @param array|null $params
     *
     * @return array
     */
    public function transfers($params = null)
    {
        self::prepareParameters($params);

        $params['recipient'] = $this->id;
        $transfers           = Transfer::all($params, $this->apiKey);

        return $transfers;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param array|null $params
     */
    protected static function prepareParameters(&$params)
    {
        // TODO: Move this method to parent
        if (is_null($params)) {
            $params = [];
        }
    }
}
