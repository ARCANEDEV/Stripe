<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\CustomerInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * Customer Object
 * @link https://stripe.com/docs/api/php#customers
 *
 * @property int                id
 * @property string             object  // "customer"
 * @property bool               livemode
 * @property ListObject         cards
 * @property int                created
 * @property int                account_balance
 * @property string             currency
 * @property string             default_card
 * @property bool               delinquent
 * @property string             description
 * @property mixed|null         discount
 * @property string             email
 * @property AttachedObject     metadata
 * @property ListObject         subscriptions
 */
class Customer extends Resource implements CustomerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Allow to check attributes while setting
     *
     * @var bool
     */
    protected $checkUnsavedAttributes = true;

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Customer
     * @link https://stripe.com/docs/api/php#retrieve_customer
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Customer
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve($id, $apiKey);
    }

    /**
     * List all Customers
     * @link https://stripe.com/docs/api/php#list_customers
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
     * Create Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @param array  $params
     * @param string|null $apiKey
     *
     * @return Customer
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate($params, $apiKey);
    }

    /**
     * Update/Save Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @returns Customer
     */
    public function save()
    {
        return self::scopedSave();
    }

    /**
     * Delete Customer
     * @link https://stripe.com/docs/api/php#delete_customer
     *
     * @param array $params
     *
     * @returns Customer
     */
    public function delete($params = [])
    {
        return self::scopedDelete($params);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add an invoice item
     *
     * @param array $params
     *
     * @returns InvoiceItem
     */
    public function addInvoiceItem($params = [])
    {
        $this->appCustomerParam($params);

        return InvoiceItem::create($params, $this->apiKey);
    }

    /**
     * Get all invoices
     *
     * @param array $params
     *
     * @returns ListObject
     */
    public function invoices($params = [])
    {
        $this->appCustomerParam($params);

        return Invoice::all($params, $this->apiKey);
    }

    /**
     * Get all invoice items
     *
     * @param array $params
     *
     * @returns array
     */
    public function invoiceItems($params = [])
    {
        $this->appCustomerParam($params);

        return InvoiceItem::all($params, $this->apiKey);
    }

    /**
     * Get all charges
     *
     * @param array $params
     *
     * @returns ListObject
     */
    public function charges($params = [])
    {
        $this->appCustomerParam($params);

        return Charge::all($params, $this->apiKey);
    }

    /**
     * Update Subscription
     *
     * @param array $params
     *
     * @returns Subscription
     */
    public function updateSubscription($params = [])
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/subscription', $params);

        $this->refreshFrom(['subscription' => $response], $apiKey, true);

        // TODO: Check if updateSubscription return one subscription or many
        return $this->subscription;
    }

    /**
     * Cancel Subscription
     *
     * @param array $params
     *
     * @returns Subscription
     */
    public function cancelSubscription($params = [])
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->delete($this->instanceUrl() . '/subscription', $params);

        $this->refreshFrom(['subscription' => $response], $apiKey, true);

        return $this->subscription;
    }

    /**
     * Delete Discount
     *
     * @returns Object
     */
    public function deleteDiscount()
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->delete($this->instanceUrl() . '/discount');

        $this->refreshFrom(['discount' => null], $apiKey, true);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add Customer ID to parameters
     *
     * @param array $params
     */
    private function appCustomerParam(&$params)
    {
        $params['customer'] = $this->id;
    }
}
