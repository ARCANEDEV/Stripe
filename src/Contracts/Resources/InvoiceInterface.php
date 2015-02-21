<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Invoice;

interface InvoiceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create an invoice
     * @link https://stripe.com/docs/api/php#create_invoice
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Invoice|array
     */
    public static function create($params = [], $options = null);

    /**
     * Retrieving an Invoice
     * @link https://stripe.com/docs/api/php#retrieve_invoice
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Invoice
     */
    public static function retrieve($id, $options = null);

    /**
     * List of all Invoices
     * @link https://stripe.com/docs/api/php#list_customer_invoices
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Update/Save an invoice
     * @link https://stripe.com/docs/api/php#update_invoice
     *
     * @param  array|string|null $options
     *
     * @return Invoice
     */
    public function save($options = null);

    /**
     * Retrieve  Upcoming Invoice
     * @link https://stripe.com/docs/api/php#retrieve_customer_invoice
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Invoice|array
     */
    public static function upcoming($params = [], $options = null);

    /**
     * Pay an invoice
     * @link https://stripe.com/docs/api/php#pay_invoice
     *
     * @param  array|string|null $options
     *
     * @return Invoice
     */
    public function pay($options = null);
}
