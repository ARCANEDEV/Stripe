<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\Account as AccountContract;
use Arcanedev\Stripe\StripeOAuth;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Account
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#account_object
 *
 * @property  null                              id
 * @property  string                            object                   // 'account'
 * @property  string|null                       business_logo
 * @property  string                            business_name
 * @property  string|null                       business_url
 * @property  bool                              charges_enabled
 * @property  string                            country
 * @property  bool                              debit_negative_balances  // managed accounts only
 * @property  \Arcanedev\Stripe\StripeObject    decline_charge_on
 * @property  string                            default_currency
 * @property  bool                              details_submitted
 * @property  string                            display_name
 * @property  string                            email
 * @property  \Arcanedev\Stripe\Collection      external_accounts        // managed accounts only
 * @property  \Arcanedev\Stripe\AttachedObject  legal_entity             // managed accounts only
 * @property  \Arcanedev\Stripe\Collection      login_links
 * @property  bool                              managed
 * @property  mixed                             payout_schedule
 * @property  mixed                             payout_statement_descriptor
 * @property  bool                              payouts_enabled
 * @property  string|null                       product_description      // managed accounts only
 * @property  string|null                       statement_descriptor
 * @property  string|null                       support_email
 * @property  string|null                       support_phone
 * @property  string|null                       support_url
 * @property  string                            timezone
 * @property  \Arcanedev\Stripe\AttachedObject  tos_acceptance           // managed accounts only
 * @property  \Arcanedev\Stripe\AttachedObject  transfer_schedule        // managed accounts only
 * @property  bool                              transfers_enabled
 * @property  \Arcanedev\Stripe\AttachedObject  verification
 */
class Account extends StripeResource implements AccountContract
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const PATH_EXTERNAL_ACCOUNTS = '/external_accounts';
    const PATH_LOGIN_LINKS = '/login_links';

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the instance url.
     *
     * @return string
     */
    public function instanceUrl()
    {
        return is_null($this['id']) ? '/v1/account' : parent::instanceUrl();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all Accounts.
     * @link   https://stripe.com/docs/api/php#list_accounts
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
     * Retrieve an Account.
     * @link   https://stripe.com/docs/api/php#retrieve_account
     *
     * @param  string|null        $id
     * @param  array|string|null  $options
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
     * Create an Account.
     * @link   https://stripe.com/docs/api/php#create_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update an Account.
     * @link   https://stripe.com/docs/api/php#update_account
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
     * Update/Save an Account.
     * @link   https://stripe.com/docs/api/php#update_account
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }

    /**
     * Delete an Account.
     * @link   https://stripe.com/docs/api/php#delete_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null)
    {
        return $this->scopedDelete($params, $options);
    }

    /**
     * Reject an Account.
     * @link   https://stripe.com/docs/api/php#reject_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function reject($params = [], $options = null)
    {
        return $this->scopedPostCall(
            $this->instanceUrl() . '/reject', $params, $options
        );
    }

    /**
     * Deauthorize the account.
     *
     * @param  array|null  $clientId
     * @param  array|null  $options
     *
     * @return \Arcanedev\Stripe\StripeObject
     */
    public function deauthorize($clientId = null, $options = null)
    {
        $params = [
            'client_id'      => $clientId,
            'stripe_user_id' => $this->id,
        ];

        return StripeOAuth::deauthorize($params, $options);
    }

    /**
     * Create an external account.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function createExternalAccount($id, $params = null, $options = null)
    {
        return self::createNestedResource($id, static::PATH_EXTERNAL_ACCOUNTS, $params, $options);
    }

    /**
     * Retrieve an external account.
     *
     * @param  string             $id
     * @param  string             $externalAccountId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function retrieveExternalAccount($id, $externalAccountId, $params = null, $options = null)
    {
        return self::retrieveNestedResource($id, static::PATH_EXTERNAL_ACCOUNTS, $externalAccountId, $params, $options);
    }

    /**
     * Update an external account.
     *
     * @param  string             $id
     * @param  string             $externalAccountId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function updateExternalAccount($id, $externalAccountId, $params = null, $options = null)
    {
        return self::updateNestedResource($id, static::PATH_EXTERNAL_ACCOUNTS, $externalAccountId, $params, $options);
    }

    /**
     * Delete an external account.
     *
     * @param  string             $id
     * @param  string             $externalAccountId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function deleteExternalAccount($id, $externalAccountId, $params = null, $options = null)
    {
        return self::deleteNestedResource($id, static::PATH_EXTERNAL_ACCOUNTS, $externalAccountId, $params, $options);
    }

    /**
     * Get all the external accounts.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection
     */
    public static function allExternalAccounts($id, $params = null, $options = null)
    {
        return self::allNestedResources($id, static::PATH_EXTERNAL_ACCOUNTS, $params, $options);
    }

    /**
     * Create a login link.
     *
     * @link  https://stripe.com/docs/api#create_login_link
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Resources\LoginLink
     */
    public static function createLoginLink($id, $params = null, $options = null)
    {
        return self::createNestedResource($id, static::PATH_LOGIN_LINKS, $params, $options);
    }
}
