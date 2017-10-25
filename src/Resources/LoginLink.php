<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\StripeResource;
use Arcanedev\Stripe\Contracts\Resources\LoginLink as LoginLinkContract;

/**
 * Class     LoginLink
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @link     https://stripe.com/docs/api#login_link_object
 *
 * @property  string  id
 * @property  string  url
 * @property  int     created  // timestamp
 */
class LoginLink extends StripeResource implements LoginLinkContract
{
    //
}
