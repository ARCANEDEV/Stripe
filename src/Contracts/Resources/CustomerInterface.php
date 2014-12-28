<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;
use Arcanedev\Stripe\Contracts\ObjectInterface;

/**
 * Customer Object Interface
 * @link https://stripe.com/docs/api/php#customers
 *
 * @property int                     id
 * @property string                  object  // "customer"
 * @property bool                    livemode
 * @property ListObjectInterface     cards
 * @property int                     created
 * @property int                     account_balance
 * @property string                  currency
 * @property string                  default_card
 * @property bool                    delinquent
 * @property string                  description
 * @property mixed|null              discount
 * @property string                  email
 * @property AttachedObjectInterface metadata
 * @property ListObjectInterface     subscriptions
 */
interface CustomerInterface
{
    /**
     * Retrieve a Customer
     * @link https://stripe.com/docs/api/php#retrieve_customer
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return CustomerInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * List all Customers
     * @link https://stripe.com/docs/api/php#list_customers
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $apiKey = null);

    /**
     * Create Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @param array  $params
     * @param string|null $apiKey
     *
     * @return CustomerInterface
     */
    public static function create($params = [], $apiKey = null);

    /**
     * Update/Save Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @returns CustomerInterface
     */
    public function save();

    /**
     * Delete Customer
     * @link https://stripe.com/docs/api/php#delete_customer
     *
     * @param array $params
     *
     * @returns CustomerInterface
     */
    public function delete($params = []);

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add an invoice item
     *
     * @param array $params
     *
     * @returns InvoiceItemInterface
     */
    public function addInvoiceItem($params = []);

    /**
     * Get all invoices
     *
     * @param array $params
     *
     * @returns ListObjectInterface
     */
    public function invoices($params = []);

    /**
     * Get all invoice items
     *
     * @param array $params
     *
     * @returns array
     */
    public function invoiceItems($params = []);

    /**
     * Get all charges
     *
     * @param array $params
     *
     * @returns ListObjectInterface
     */
    public function charges($params = []);

    /**
     * Update a Subscription
     *
     * @param array $params
     *
     * @return SubscriptionInterface
     */
    public function updateSubscription($params = []);

    /**
     * Cancel Subscription
     *
     * @param array $params
     *
     * @return SubscriptionInterface
     */
    public function cancelSubscription($params = []);

    /**
     * Delete Discount
     *
     * @returns ObjectInterface
     */
    public function deleteDiscount();
}
