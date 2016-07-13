<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  CustomerInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CustomerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Customers.
     * @link   https://stripe.com/docs/api/php#list_customers
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Customer.
     * @link   https://stripe.com/docs/api/php#retrieve_customer
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create Customer.
     * @link   https://stripe.com/docs/api/php#create_customer
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update a Customer.
     * @link   https://stripe.com/docs/api/php#create_customer
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

    /**
     * Update/Save Customer.
     * @link   https://stripe.com/docs/api/php#create_customer
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Delete Customer.
     * @link   https://stripe.com/docs/api/php#delete_customer
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null);

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add an invoice item.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Resources\InvoiceItem|array
     */
    public function addInvoiceItem($params = []);

    /**
     * Get all invoices.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public function invoices($params = []);

    /**
     * Get all invoice items.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public function invoiceItems($params = []);

    /**
     * Get all charges.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public function charges($params = []);

    /**
     * Update a Subscription.
     *
     * @param  array|null  $params
     *
     * @return \Arcanedev\Stripe\Resources\Subscription
     */
    public function updateSubscription($params = []);

    /**
     * Cancel Subscription.
     *
     * @param  array|null  $params
     *
     * @return \Arcanedev\Stripe\Resources\Subscription
     */
    public function cancelSubscription($params = []);

    /**
     * Delete Discount.
     *
     * @return \Arcanedev\Stripe\StripeObject
     */
    public function deleteDiscount();
}
