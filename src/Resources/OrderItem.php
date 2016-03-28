<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\StripeResource;

/**
 * Class     OrderItem
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  object       // 'order_item'
 * @property  int     amount
 * @property  string  currency
 * @property  string  description
 * @property  string  parent
 * @property  int     quantity
 * @property  string  type         // 'sku', 'tax', 'shipping' or 'discount'
 */
class OrderItem extends StripeResource
{
    //
}
