<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Bases\ExternalAccount;

/**
 * Class     AlipayAccount
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#alipay_accounts
 *
 * @property  string                            id
 * @property  string                            object            // alipay_account
 * @property  int                               created           // timestamp
 * @property  string                            customer
 * @property  string                            fingerprint
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  int                               payment_amount
 * @property  string                            payment_currency
 * @property  bool                              reusable
 * @property  bool                              used
 * @property  string                            username
 */
class AlipayAccount extends ExternalAccount
{
    //
}
