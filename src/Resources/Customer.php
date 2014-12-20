<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\ObjectAttached;
use Arcanedev\Stripe\ObjectList;

/**
 * @property int                id
 * @property string             object
 * @property bool               livemode
 * @property ObjectList         cards
 * @property int                created
 * @property int                account_balance
 * @property string             currency
 * @property string             default_card
 * @property bool               delinquent
 * @property string             description
 * @property mixed|null         discount
 * @property string             email
 * @property ObjectAttached     metadata
 * @property bool               deleted
 * @property mixed|null         subscription
 * @property mixed|null         subscriptions
 */
class Customer extends Resource
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $id The ID of the customer to retrieve.
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
     * @param array|null $params
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
     * @param array|null $params
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
     * @returns InvoiceItem The resulting invoice item.
     */
    public function addInvoiceItem($params = null)
    {
        if ( is_null($params) ) {
            $params = [];
        }

        $params['customer'] = $this->id;

        return InvoiceItem::create($params, $this->apiKey);
    }

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_Invoices.
     */
    public function invoices($params = null)
    {
        if ( is_null($params) ) {
            $params = [];
        }

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
        if ( is_null($params) ) {
            $params = [];
        }

        $params['customer'] = $this->id;

        return InvoiceItem::all($params, $this->apiKey);
    }

    /**
     * @param array|null $params
     *
     * @returns array An array of the customer's Stripe_Charges.
     */
    public function charges($params = null)
    {
        if ( is_null($params) ) {
            $params = [];
        }

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
        $requestor  = new Requestor($this->apiKey);
        $url        = $this->instanceUrl() . '/subscription';

        list($response, $apiKey) = $requestor->post($url, $params);
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
        $requestor  = new Requestor($this->apiKey);
        $url        = $this->instanceUrl() . '/subscription';

        list($response, $apiKey) = $requestor->delete($url, $params);
        $this->refreshFrom(['subscription' => $response], $apiKey, true);

        return $this->subscription;
    }

    /**
     * @returns Customer The updated customer.
     */
    public function deleteDiscount()
    {
        $requestor  = new Requestor($this->apiKey);
        $url        = $this->instanceUrl() . '/discount';
        list($response, $apiKey) = $requestor->delete($url);

        $this->refreshFrom(['discount' => null], $apiKey, true);
    }
}
