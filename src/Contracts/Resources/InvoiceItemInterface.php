<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\InvoiceItem;

interface InvoiceItemInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an Invoice Item
     * @link https://stripe.com/docs/api/php#retrieve_invoiceitem
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return InvoiceItem
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Invoice Items
     * @link https://stripe.com/docs/api/php#list_invoiceitems
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @return ListObject
     */
    public static function all($params = [], $options = null);

    /**
     * Create an Invoice Item
     * @link https://stripe.com/docs/api/php#create_invoiceitem
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return InvoiceItem
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save an Invoice Item
     * @link https://stripe.com/docs/api/php#update_invoiceitem
     *
     * @return InvoiceItem
     */
    public function save();

    /**
     * Delete an Invoice Item
     * @link https://stripe.com/docs/api/php#delete_invoiceitem
     *
     * @param array $params
     *
     * @return InvoiceItem
     */
    public function delete($params = []);
}
