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
     * Retrieve an Account.
     * @link   https://stripe.com/docs/api/php#retrieve_account
     *
     * @param  string|null        $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id = null, $options = null);

    /**
     * Create an Account.
     * @link   https://stripe.com/docs/api/php#create_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null);

    /**
     * Save an Account.
     * @link   https://stripe.com/docs/api/php#update_account
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Get all Accounts.
     * @link   https://stripe.com/docs/api/php#list_accounts
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null);

    /**
     * Delete an Account.
     * @link   https://stripe.com/docs/api/php#delete_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null);

    /**
     * Reject an Account.
     * @link   https://stripe.com/docs/api/php#reject_account
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function reject($params = null, $options = null);
}
