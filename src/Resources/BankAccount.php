<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Bases\ExternalAccount;
use Arcanedev\Stripe\Contracts\Resources\BankAccountInterface;

/**
 * Class     BankAccount
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#bank_accounts
 *
 * @property  string                            id
 * @property  string                            object                // "bank_account"
 * @property  string                            account
 * @property  string                            account_holder_name
 * @property  string                            account_holder_type   // "individual" or "company"
 * @property  string                            bank_name
 * @property  string                            country
 * @property  string                            currency
 * @property  bool                              default_for_currency
 * @property  string                            fingerprint
 * @property  string                            last4
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            routing_number
 * @property  string                            status                // 'new', 'validated', 'verified', or 'errored'
 */
class BankAccount extends ExternalAccount implements BankAccountInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
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
    public function verify($params = [], $options = null)
    {
        return $this->scopedPostCall(
            $this->instanceUrl() . '/verify', $params, $options
        );
    }
}
