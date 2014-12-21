<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface InvoiceItemsInterface
{
    /**
     * @param string      $id The ID of the invoice item to retrieve.
     * @param string|null $apiKey
     *
     * @return InvoiceItemsInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of InvoiceItems.
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return InvoiceItemsInterface The created invoice item.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @return InvoiceItemsInterface The saved invoice item.
     */
    public function save();

    /**
     * @param array|null $params
     *
     * @return InvoiceItemsInterface The deleted invoice item.
     */
    public function delete($params = null);
}
