<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  AccountInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface AccountInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an account.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_account
     *
     * @param  string|null  $apiKey
     *
     * @return self
     */
    public static function retrieve($apiKey = null);

    /**
     * Create an account.
     *
     * @link   https://stripe.com/docs/api/php#create_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Save an account.
     *
     * @link   https://stripe.com/docs/api/php#update_account
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Get all accounts.
     *
     * @link   https://stripe.com/docs/api/php#list_accounts
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null);

    /**
     * Delete an account.
     *
     * @link   https://stripe.com/docs/api/php#delete_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null);

    /**
     * Reject an account.
     *
     * @link   https://stripe.com/docs/api/php#reject_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function reject($params = null, $options = null);
}
