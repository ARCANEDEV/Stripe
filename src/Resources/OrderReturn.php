<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\OrderReturnInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     OrderReturn
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#order_return_object
 *
 * @property  string                                  id
 * @property  string                                  object    // 'order_return'
 * @property  int                                     amount
 * @property  int                                     created   // timestamp
 * @property  string                                  currency
 * @property  array                                   items
 * @property  bool                                    livemode
 * @property  string                                  order
 * @property  string                                  refund
 */
class OrderReturn extends StripeResource implements OrderReturnInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an order return.
     * @link   https://stripe.com/docs/api/php#retrieve_order_return
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * List all order returns.
     * @link   https://stripe.com/docs/api/php#list_order_returns
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
