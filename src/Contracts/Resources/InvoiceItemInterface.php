<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * InvoiceItem Object Interface
 * @link https://stripe.com/docs/api/php#invoice_item_object
 *
 * @property string                  id
 * @property string                  object // "invoiceitem"
 * @property bool                    livemode
 * @property int                     amount
 * @property string                  currency
 * @property string                  customer
 * @property int                     date
 * @property bool                    proration
 * @property string                  description
 * @property string                  invoice
 * @property AttachedObjectInterface metadata
 * @property PlanInterface           plan
 * @property int                     quantity
 * @property string                  subscription
 */
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
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return InvoiceItemInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * List all Invoice Items
     * @link https://stripe.com/docs/api/php#list_invoiceitems
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $apiKey = null);

    /**
     * Create an Invoice Item
     * @link https://stripe.com/docs/api/php#create_invoiceitem
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return InvoiceItemInterface
     */
    public static function create($params = [], $apiKey = null);

    /**
     * Update/Save an Invoice Item
     * @link https://stripe.com/docs/api/php#update_invoiceitem
     *
     * @return InvoiceItemInterface
     */
    public function save();

    /**
     * Delete an Invoice Item
     * @link https://stripe.com/docs/api/php#delete_invoiceitem
     *
     * @param array $params
     *
     * @return InvoiceItemInterface
     */
    public function delete($params = []);
}
