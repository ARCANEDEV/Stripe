<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface InvoiceInterface
{
    /**
     * @param string      $id The ID of the invoice to retrieve.
     * @param string|null $apiKey
     *
     * @return InvoiceInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Invoices.
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return InvoiceInterface The created invoice.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @return InvoiceInterface The saved invoice.
     */
    public function save();

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return InvoiceInterface The upcoming invoice.
     */
    public static function upcoming($params = null, $apiKey = null);

    /**
     * @return InvoiceInterface The paid invoice.
     */
    public function pay();
}
