<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\InvoiceItem;
use Arcanedev\Stripe\Resources\Subscription;

interface CustomerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Customer
     * @link https://stripe.com/docs/api/php#retrieve_customer
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Customer
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Customers
     * @link https://stripe.com/docs/api/php#list_customers
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Customer|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @return Customer
     */
    public function save();

    /**
     * Delete Customer
     * @link https://stripe.com/docs/api/php#delete_customer
     *
     * @param  array|null $params
     *
     * @return Customer
     */
    public function delete($params = []);

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add an invoice item
     *
     * @param  array $params
     *
     * @return InvoiceItem|array
     */
    public function addInvoiceItem($params = []);

    /**
     * Get all invoices
     *
     * @param  array $params
     *
     * @return ListObject|array
     */
    public function invoices($params = []);

    /**
     * Get all invoice items
     *
     * @param  array $params
     *
     * @return ListObject|array
     */
    public function invoiceItems($params = []);

    /**
     * Get all charges
     *
     * @param  array $params
     *
     * @return ListObject|array
     */
    public function charges($params = []);

    /**
     * Update a Subscription
     *
     * @param  array|null $params
     *
     * @return Subscription
     */
    public function updateSubscription($params = []);

    /**
     * Cancel Subscription
     *
     * @param  array|null $params
     *
     * @return Subscription
     */
    public function cancelSubscription($params = []);

    /**
     * Delete Discount
     *
     * @return Object
     */
    public function deleteDiscount();
}
