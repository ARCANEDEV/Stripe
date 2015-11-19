<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Bases\ExternalAccount;
use Arcanedev\Stripe\Contracts\Resources\BankAccountInterface;

/**
 * Class     BankAccount
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @link     https://stripe.com/docs/api#bank_accounts
 *
 * @property  string                            id
 * @property  string                            object                // 'bank_account'
 * @property  string                            account
 * @property  string                            country
 * @property  string                            currency
 * @property  bool                              default_for_currency
 * @property  string                            last4
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            status                // 'new', 'validated', 'verified', or 'errored'
 * @property  string                            bank_name
 * @property  string                            fingerprint
 * @property  string                            routing_number
 */
class BankAccount extends ExternalAccount implements BankAccountInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the verified bank account.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function verify($params = null, $options = null)
    {
        $url = $this->instanceUrl() . '/verify';

        return $this->scopedPostCall($url, $params, $options);
    }
}
