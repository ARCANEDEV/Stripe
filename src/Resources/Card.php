<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Bases\ExternalAccount;
use Arcanedev\Stripe\Contracts\Resources\CardInterface;

/**
 * Class     Card
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#card_object
 *
 * @property  string                            id
 * @property  string                            object                // 'card'
 * @property  string                            account               // managed accounts only
 * @property  string                            address_city
 * @property  string                            address_country
 * @property  string                            address_line1
 * @property  string                            address_line1_check
 * @property  string                            address_line2
 * @property  string                            address_state
 * @property  string                            address_zip
 * @property  string                            address_zip_check
 * @property  string                            brand
 * @property  string                            country
 * @property  string                            currency              // managed accounts only
 * @property  string                            customer
 * @property  string                            cvc_check             // 'pass', 'fail', 'unavailable', or 'unchecked'
 * @property  bool                              default_for_currency  // managed accounts only
 * @property  string                            dynamic_last4
 * @property  int                               exp_month
 * @property  int                               exp_year
 * @property  string                            fingerprint
 * @property  string                            funding               // 'credit', 'debit', 'prepaid', or 'unknown'
 * @property  string                            last4
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            name
 * @property  string                            recipient
 * @property  string|null                       tokenization_method
 */
class Card extends ExternalAccount implements CardInterface
{
    //
}
