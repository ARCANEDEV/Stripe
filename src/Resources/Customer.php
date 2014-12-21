<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\CustomerInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * @property int                id
 * @property string             object
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
 * @property bool               deleted
 * @property mixed|null         subscription
 * @property mixed|null         subscriptions
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
     * @param string      $id     The ID of the customer to retrieve.
     * @param string|null $apiKey
     *
     * @return Customer
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Customers.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return Customer The created customer.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * @returns Customer The saved customer.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * @param array|null $params
     *
     * @returns Customer The deleted customer.
     */
    public function delete($params = null)
    {
        $class = get_class();

        return self::scopedDelete($class, $params);
    }

    /**
     * @param array|null $params
     *
     * @returns InvoiceItems The resulting invoice item.
     */
    public function addInvoiceItem($params = null)
    {
        self::prepareParameters($params);

        $params['customer'] = $this->id;

        return InvoiceItems::create($params, $this->apiKey);
    }

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_Invoices.
     */
    public function invoices($params = null)
    {
        self::prepareParameters($params);

        $params['customer'] = $this->id;

        return Invoice::all($params, $this->apiKey);
    }

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_InvoiceItems.
     */
    public function invoiceItems($params = null)
    {
        self::prepareParameters($params);

        $params['customer'] = $this->id;

        return InvoiceItems::all($params, $this->apiKey);
    }

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_Charges.
     */
    public function charges($params = null)
    {
        self::prepareParameters($params);

        $params['customer'] = $this->id;

        return Charge::all($params, $this->apiKey);
    }

    /**
     * @param array|null $params
     *
     * @returns Subscription The updated subscription.
     */
    public function updateSubscription($params = null)
    {
        $url    = $this->instanceUrl() . '/subscription';

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($url, $params);

        $this->refreshFrom(['subscription' => $response], $apiKey, true);

        return $this->subscription;
    }

    /**
     * @param array|null $params
     *
     * @returns Subscription The cancelled subscription.
     */
    public function cancelSubscription($params = null)
    {
        $url    = $this->instanceUrl() . '/subscription';

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->delete($url, $params);

        $this->refreshFrom(['subscription' => $response], $apiKey, true);

        return $this->subscription;
    }

    /**
     * @returns Customer The updated customer.
     */
    public function deleteDiscount()
    {
        $url    = $this->instanceUrl() . '/discount';
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->delete($url);

        $this->refreshFrom(['discount' => null], $apiKey, true);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param array|null $params
     */
    protected static function prepareParameters(&$params)
    {
        // TODO: Move this method to parent
        if (is_null($params)) {
            $params = [];
        }
    }
}
