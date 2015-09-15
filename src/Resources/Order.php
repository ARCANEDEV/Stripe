<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\OrderInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Order
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api#orders
 *
 * @property  string          object                    // 'order'
 * @property  bool            livemode
 * @property  int             amount
 * @property  int             created
 * @property  int             updated
 * @property  string          currency
 * @property  array           items                     // todo: convert it to order_items (https://stripe.com/docs/api#order_items)
 * @property  AttachedObject  metadata
 * @property  string          status                    // 'paid', 'fulfilled', or 'refunded'.
 * @property  string          customer
 * @property  string          email
 * @property  string          selected_shipping_method
 * @property  Collection      shipping_methods
 * @property  array           shipping
 */
class Order extends StripeResource implements OrderInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieves the details of an existing order.
     *
     * @link   https://stripe.com/docs/api#retrieve_order
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
     * Creates a new order object.
     *
     * @link   https://stripe.com/docs/api#create_order
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
     * Update an order.
     *
     * @link   https://stripe.com/docs/api#update_order
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
     * List all orders.
     *
     * @link   https://stripe.com/docs/api#list_orders
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Pay an order.
     *
     * @link   https://stripe.com/docs/api#pay_order
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function pay($params = null, $options = null)
    {
        $url = $this->instanceUrl() . '/pay';

        list($response, $options) = $this->request('post', $url, $params, $options);
        $this->refreshFrom($response, $options);

        return $this;
    }
}
