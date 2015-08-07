<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\AccountInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class Account
 * @package Arcanedev\Stripe\Resources
 * @link https://stripe.com/docs/api/php#account_object
 *
 * @property null   id
 * @property string object
 * @property bool   charges_enabled
 * @property string country
 * @property array  currencies_supported
 * @property string default_currency
 * @property bool   details_submitted
 * @property bool   transfers_enabled
 * @property string display_name
 * @property string email
 * @property string statement_descriptor
 * @property string timezone
 */
class Account extends StripeResource implements AccountInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function instanceUrl()
    {
        if (is_null($this['id'])) {
            return '/v1/account';
        }

        return parent::instanceUrl();
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an account
     * @link https://stripe.com/docs/api/php#retrieve_account
     *
     * @param  string|null       $id
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function retrieve($id = null, $options = null)
    {
        if (
            ! $options &&
            is_string($id) &&
            substr($id, 0, 3) === 'sk_'
        ) {
            $options = $id;
            $id      = null;
        }

        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create an account
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Save an account
     *
     * @param  array|string|null $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }

    /**
     * Deleted an account.
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Account
     */
    public function delete($params = null, $options = null)
    {
        return $this->scopedDelete($params, $options);
    }

    /**
     * Get all accounts
     *
     * @param  array|null $params
     * @param  array|string|null $options
     *
     * @return Collection|self[]
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
