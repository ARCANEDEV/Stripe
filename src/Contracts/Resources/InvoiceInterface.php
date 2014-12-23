<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Invoice Object Interface
 * @link https://stripe.com/docs/api/php#invoices
 *
 * @property string                  id
 * @property string                  object // "invoice"
 * @property bool                    livemode
 * @property int                     amount_due
 * @property int                     attempt_count
 * @property bool                    attempted
 * @property bool                    closed
 * @property string                  currency
 * @property string                  customer
 * @property int                     date
 * @property bool                    forgiven
 * @property ListObjectInterface     lines
 * @property bool                    paid
 * @property int                     period_end
 * @property int                     period_start
 * @property int                     starting_balance
 * @property int                     subtotal
 * @property int                     total
 * @property int                     application_fee
 * @property string                  charge
 * @property string                  description
 * @property Object                  discount             // Discount Object
 * @property int                     ending_balance
 * @property int                     next_payment_attempt
 * @property string                  receipt_number
 * @property string                  statement_descriptor
 * @property string                  subscription
 * @property int                     webhooks_delivered_at
 * @property AttachedObjectInterface metadata
 */
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
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return InvoiceInterface
     */
    public static function create($params = [], $apiKey = null);

    /**
     * Retrieving an Invoice
     * @link https://stripe.com/docs/api/php#retrieve_invoice
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return InvoiceInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * List of all Invoices
     * @link https://stripe.com/docs/api/php#list_customer_invoices
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $apiKey = null);

    /**
     * Retrieve  Upcoming Invoice
     * @link https://stripe.com/docs/api/php#retrieve_customer_invoice
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return InvoiceInterface
     */
    public static function upcoming($params = [], $apiKey = null);

    /**
     * Update/Save an invoice
     * @link https://stripe.com/docs/api/php#update_invoice
     *
     * @return InvoiceInterface
     */
    public function save();

    /**
     * Pay an invoice
     * @link https://stripe.com/docs/api/php#pay_invoice
     *
     * @return InvoiceInterface
     */
    public function pay();
}
