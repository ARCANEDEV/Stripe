<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\InvoiceInterface;
use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Class Invoice
 * @package Arcanedev\Stripe\Resources
 * @link https://stripe.com/docs/api/php#invoices
 *
 * @property string         id
 * @property string         object // "invoice"
 * @property bool           livemode
 * @property int            amount_due
 * @property int            attempt_count
 * @property bool           attempted
 * @property bool           closed
 * @property string         currency
 * @property string         customer
 * @property int            date
 * @property bool           forgiven
 * @property Collection     lines
 * @property bool           paid
 * @property int            period_end
 * @property int            period_start
 * @property int            starting_balance
 * @property int            subtotal
 * @property int            total
 * @property int            application_fee
 * @property string         charge
 * @property string         description
 * @property Object         discount             // Discount Object
 * @property int            ending_balance
 * @property int            next_payment_attempt
 * @property string         receipt_number
 * @property string         statement_descriptor
 * @property string         subscription
 * @property int            webhooks_delivered_at
 * @property AttachedObject metadata
 * @property int            tax
 * @property float          tax_percent
 */
class Invoice extends Resource implements InvoiceInterface
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
    public static function create($params = [], $options = null)
    {
        return parent::scopedCreate($params, $options);
    }

    /**
     * Retrieving an Invoice
     * @link https://stripe.com/docs/api/php#retrieve_invoice
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Invoice
     */
    public static function retrieve($id, $options = null)
    {
        return parent::scopedRetrieve($id, $options);
    }

    /**
     * List of all Invoices
     * @link https://stripe.com/docs/api/php#list_customer_invoices
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return parent::scopedAll($params, $options);
    }

    /**
     * Update/Save an invoice
     * @link https://stripe.com/docs/api/php#update_invoice
     *
     * @param  array|string|null $options
     *
     * @return Invoice
     */
    public function save($options = null)
    {
        return parent::scopedSave($options);
    }

    /**
     * Retrieve  Upcoming Invoice
     * @link https://stripe.com/docs/api/php#retrieve_customer_invoice
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Invoice|array
     */
    public static function upcoming($params = [], $options = null)
    {
        $url  = parent::classUrl(get_class()) . '/upcoming';

        list($response, $opts) = self::staticRequest('get', $url, $params, $options);

        return Util::convertToStripeObject($response, $opts);
    }

    /**
     * Pay an invoice
     * @link https://stripe.com/docs/api/php#pay_invoice
     *
     * @param  array|string|null $options
     *
     * @return Invoice
     */
    public function pay($options = null)
    {
        $url = $this->instanceUrl() . '/pay';

        list($response, $opts) = $this->request('post', $url, [], $options);

        $this->refreshFrom($response, $opts);

        return $this;
    }
}
