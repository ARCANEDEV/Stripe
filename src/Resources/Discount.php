<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\StripeResource;

/**
 * Class     Discount
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#discount_object
 *
 * @property  string                              id
 * @property  string                              object        // 'discount'
 * @property  \Arcanedev\Stripe\Resources\Coupon  coupon
 * @property  string                              customer
 * @property  int|null                            end           // timestamp
 * @property  int                                 start         // timestamp
 * @property  string|null                         subscription
 */
class Discount extends StripeResource
{
    //
}
