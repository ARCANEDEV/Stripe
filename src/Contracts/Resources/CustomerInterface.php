<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface CustomerInterface
{
    /**
     * @param string      $id     The ID of the customer to retrieve.
     * @param string|null $apiKey
     *
     * @return CustomerInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Customers.
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return CustomerInterface The created customer.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @returns CustomerInterface The saved customer.
     */
    public function save();

    /**
     * @param array|null $params
     *
     * @returns CustomerInterface The deleted customer.
     */
    public function delete($params = null);

    /**
     * @param array|null $params
     *
     * @returns InvoiceItemsInterface The resulting invoice item.
     */
    public function addInvoiceItem($params = null);

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_Invoices.
     */
    public function invoices($params = null);

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_InvoiceItems.
     */
    public function invoiceItems($params = null);

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_Charges.
     */
    public function charges($params = null);

    /**
     * @param array|null $params
     *
     * @returns SubscriptionInterface The updated subscription.
     */
    public function updateSubscription($params = null);

    /**
     * @param array|null $params
     *
     * @returns SubscriptionInterface The cancelled subscription.
     */
    public function cancelSubscription($params = null);

    /**
     * @returns CustomerInterface The updated customer.
     */
    public function deleteDiscount();
}
