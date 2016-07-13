<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  InvoiceInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface InvoiceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List of all Invoices.
     * @link   https://stripe.com/docs/api/php#list_customer_invoices
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieving an Invoice.
     * @link   https://stripe.com/docs/api/php#retrieve_invoice
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create an invoice.
     * @link   https://stripe.com/docs/api/php#create_invoice
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update an invoice.
     * @link   https://stripe.com/docs/api/php#update_invoice
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null);

    /**
     * Update/Save an Invoice.
     * @link   https://stripe.com/docs/api/php#update_invoice
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Retrieve Upcoming Invoice.
     * @link   https://stripe.com/docs/api/php#retrieve_customer_invoice
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function upcoming($params = [], $options = null);

    /**
     * Pay an Invoice.
     * @link   https://stripe.com/docs/api/php#pay_invoice
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function pay($options = null);
}
