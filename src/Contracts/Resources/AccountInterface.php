<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Account;

interface AccountInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an account
     * @link https://stripe.com/docs/api/php#retrieve_account
     *
     * @param  string|null $apiKey
     *
     * @return Account
     */
    public static function retrieve($apiKey = null);

    /**
     * Create an account
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Account
     */
    public static function create($params = null, $options = null);

    /**
     * Save an account
     *
     * @param  array|string|null $options
     *
     * @return Account
     */
    public function save($options = null);

    /**
     * Get all accounts
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|Account[]
     */
    public static function all($params = null, $options = null);
}
