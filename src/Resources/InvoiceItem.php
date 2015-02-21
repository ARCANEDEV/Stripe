<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\InvoiceItemInterface;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resource;

/**
 * InvoiceItem Object
 * @link https://stripe.com/docs/api/php#invoice_item_object
 *
 * @property string         id
 * @property string         object // "invoiceitem"
 * @property bool           livemode
 * @property int            amount
 * @property string         currency
 * @property string         customer
 * @property int            date
 * @property bool           proration
 * @property string         description
 * @property string         invoice
 * @property AttachedObject metadata
 * @property Plan           plan
 * @property int            quantity
 * @property string         subscription
 */
class InvoiceItem extends Resource implements InvoiceItemInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the endpoint URL for the given class.
     *
     * @param string $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return '/v1/invoiceitems';
    }

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
    public static function retrieve($id, $options = null)
    {
        return parent::scopedRetrieve($id, $options);
    }

    /**
     * List all Invoice Items
     * @link https://stripe.com/docs/api/php#list_invoiceitems
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return parent::scopedAll($params, $options);
    }

    /**
     * Create an Invoice Item
     * @link https://stripe.com/docs/api/php#create_invoiceitem
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @return InvoiceItem|array
     */
    public static function create($params = [], $options = null)
    {
        return parent::scopedCreate($params, $options);
    }

    /**
     * Update/Save an Invoice Item
     * @link https://stripe.com/docs/api/php#update_invoiceitem
     *
     * @param  array|string|null $options
     *
     * @return InvoiceItem
     */
    public function save($options = null)
    {
        return parent::scopedSave($options);
    }

    /**
     * Delete an Invoice Item
     * @link https://stripe.com/docs/api/php#delete_invoiceitem
     *
     * @param  array $params
     * @param  array|string|null $options
     *
     * @return InvoiceItem
     */
    public function delete($params = [], $options = null)
    {
        return parent::scopedDelete($params, $options);
    }
}
