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
 * @property  string  id
 * @property  string  object                // "card"
 * @property  string  brand
 * @property  int     exp_month
 * @property  int     exp_year
 * @property  string  fingerprint
 * @property  string  funding
 * @property  string  last4
 * @property  string  address_city
 * @property  string  address_country
 * @property  string  address_line1
 * @property  string  address_line1_check
 * @property  string  address_line2
 * @property  string  address_state
 * @property  string  address_zip
 * @property  string  address_zip_check
 * @property  string  country
 * @property  string  customer
 * @property  string  cvc_check
 * @property  string  dynamic_last4
 * @property  string  name
 * @property  string  recipient
 *
 * @todo:     Update the properties.
 */
class Card extends ExternalAccount implements CardInterface
{
    //
}
