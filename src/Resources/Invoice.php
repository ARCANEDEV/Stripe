<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\InvoiceInterface;
use Arcanedev\Stripe\StripeResource;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Class     Invoice
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#invoice_object
 *
 * @property  string                                       id
 * @property  string                                       object                       // 'invoice'
 * @property  int                                          amount_due
 * @property  int                                          attempt_count
 * @property  bool                                         attempted
 * @property  int                                          application_fee
 * @property  string                                       charge
 * @property  bool                                         closed
 * @property  string                                       currency
 * @property  string                                       customer
 * @property  int                                          date                         // timestamp
 * @property  string                                       description
 * @property  \Arcanedev\Stripe\Resources\Discount|null    discount
 * @property  int                                          ending_balance
 * @property  bool                                         forgiven
 * @property  \Arcanedev\Stripe\Collection                 lines
 * @property  bool                                         livemode
 * @property  \Arcanedev\Stripe\AttachedObject             metadata
 * @property  int                                          next_payment_attempt
 * @property  bool                                         paid
 * @property  int                                          period_end                   // timestamp
 * @property  int                                          period_start                 // timestamp
 * @property  string                                       receipt_number
 * @property  int                                          starting_balance
 * @property  string                                       statement_descriptor
 * @property  string                                       subscription
 * @property  int                                          subscription_proration_date
 * @property  int                                          subtotal
 * @property  int                                          tax
 * @property  float                                        tax_percent
 * @property  int                                          total
 * @property  int                                          webhooks_delivered_at        // timestamp
 */
class Invoice extends StripeResource implements InvoiceInterface
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
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieving an Invoice
     * @link   https://stripe.com/docs/api/php#retrieve_invoice
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create an Invoice.
     * @link   https://stripe.com/docs/api/php#create_invoice
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

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
    public static function update($id, $params = [], $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Update/Save an invoice.
     * @link   https://stripe.com/docs/api/php#update_invoice
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }

    /**
     * Retrieve Upcoming Invoice.
     * @link   https://stripe.com/docs/api/php#retrieve_customer_invoice
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function upcoming($params = [], $options = null)
    {
        /** @var \Arcanedev\Stripe\Http\Response $response */
        list($response, $opts) = self::staticRequest(
            'get', self::classUrl(get_class()) . '/upcoming', $params, $options
        );

        $object = Util::convertToStripeObject($response->getJson(), $opts);
        $object->setLastResponse($response);

        return $object;
    }

    /**
     * Pay an Invoice.
     * @link   https://stripe.com/docs/api/php#pay_invoice
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function pay($options = null)
    {
        list($response, $opts) = $this->request('post',
            $this->instanceUrl() . '/pay', [], $options
        );
        $this->refreshFrom($response, $opts);

        return $this;
    }
}
