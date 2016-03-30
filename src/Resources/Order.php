<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\OrderInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Order
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#order_object
 *
 * @property  string                                   id
 * @property  string                                   object                    // 'order'
 * @property  int                                      amount
 * @property  string                                   application
 * @property  int                                      application_fee
 * @property  string                                   charge
 * @property  int                                      created                   // timestamp
 * @property  string                                   currency
 * @property  string                                   customer
 * @property  string                                   email
 * @property  string                                   external_coupon_code
 * @property  \Arcanedev\Stripe\Resources\OrderItem[]  items
 * @property  bool                                     livemode
 * @property  \Arcanedev\Stripe\AttachedObject         metadata
 * @property  string                                   selected_shipping_method
 * @property  array                                    shipping
 * @property  \Arcanedev\Stripe\Collection             shipping_methods
 * @property  string                                   status                    // 'paid', 'fulfilled', or 'refunded'.
 * @property  mixed                                    status_transitions
 * @property  int                                      updated                   // timestamp
 */
class Order extends StripeResource implements OrderInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieves the details of an existing Order.
     * @link   https://stripe.com/docs/api/php#retrieve_order
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
     * Create a new Order.
     * @link   https://stripe.com/docs/api/php#create_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update an Order.
     * @link   https://stripe.com/docs/api/php#update_order
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }

    /**
     * List all Orders.
     * @link   https://stripe.com/docs/api/php#list_orders
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Pay an Order.
     * @link   https://stripe.com/docs/api/php#pay_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function pay($params = null, $options = null)
    {
        list($response, $options) = $this->request(
            'post', $this->instanceUrl() . '/pay', $params, $options
        );
        $this->refreshFrom($response, $options);

        return $this;
    }
}
