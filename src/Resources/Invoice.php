<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\InvoiceInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Invoice Object
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
 * @property ListObject     lines
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
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Invoice
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate($params, $apiKey);
    }

    /**
     * Retrieving an Invoice
     * @link https://stripe.com/docs/api/php#retrieve_invoice
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Invoice
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve($id, $apiKey);
    }

    /**
     * List of all Invoices
     * @link https://stripe.com/docs/api/php#list_customer_invoices
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        return self::scopedAll($params, $apiKey);
    }

    /**
     * Update/Save an invoice
     * @link https://stripe.com/docs/api/php#update_invoice
     *
     * @return Invoice
     */
    public function save()
    {
        return self::scopedSave();
    }

    /**
     * Retrieve  Upcoming Invoice
     * @link https://stripe.com/docs/api/php#retrieve_customer_invoice
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Invoice
     */
    public static function upcoming($params = [], $apiKey = null)
    {
        $url = self::classUrl(get_class()) . '/upcoming';

        list($response, $apiKey) = Requestor::make($apiKey)
            ->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * Pay an invoice
     * @link https://stripe.com/docs/api/php#pay_invoice
     *
     * @return Invoice
     */
    public function pay()
    {
        $url = $this->instanceUrl() . '/pay';

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($url);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }
}
