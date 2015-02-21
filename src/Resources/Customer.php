<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\CustomerInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * Customer Object
 * @link https://stripe.com/docs/api/php#customers
 *
 * @property int            id
 * @property string         object  // "customer"
 * @property bool           livemode
 * @property Collection     sources
 * @property int            created
 * @property int            account_balance
 * @property string         currency
 * @property string         default_card
 * @property bool           delinquent
 * @property string         description
 * @property Discount|null  discount
 * @property string         email
 * @property AttachedObject metadata
 * @property Collection     subscriptions
 * @property Subscription   subscription    // It's for updateSubscription and cancelSubscription
 */
class Customer extends Resource implements CustomerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const END_POINT_SUBSCRIPTION = 'subscription';
    const END_POINT_DISCOUNT     = 'discount';

    /**
     * Allow to check attributes while setting
     *
     * @var bool
     */
    protected $checkUnsavedAttributes = true;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Subscription URL
     *
     * @throws InvalidRequestException
     *
     * @return string
     */
    private function getSubscriptionUrl()
    {
        return $this->instanceUrl() . '/' . self::END_POINT_SUBSCRIPTION;
    }

    /**
     * Get Discount URL
     *
     * @throws InvalidRequestException
     *
     * @return string
     */
    private function getDiscountUrl()
    {
        return $this->instanceUrl() . '/' . self::END_POINT_DISCOUNT;
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Customer
     * @link https://stripe.com/docs/api/php#retrieve_customer
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Customer
     */
    public static function retrieve($id, $options = null)
    {
        return parent::scopedRetrieve($id, $options);
    }

    /**
     * List all Customers
     * @link https://stripe.com/docs/api/php#list_customers
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Create Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Customer|array
     */
    public static function create($params = [], $options = null)
    {
        return parent::scopedCreate($params, $options);
    }

    /**
     * Update/Save Customer
     * @link https://stripe.com/docs/api/php#create_customer
     *
     * @param  array|string|null $options
     *
     * @return Customer
     */
    public function save($options = null)
    {
        return parent::scopedSave($options);
    }

    /**
     * Delete Customer
     * @link https://stripe.com/docs/api/php#delete_customer
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Customer
     */
    public function delete($params = [], $options = null)
    {
        return parent::scopedDelete($params, $options);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Relationships Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add an invoice item
     *
     * @param  array $params
     *
     * @return InvoiceItem|array
     */
    public function addInvoiceItem($params = [])
    {
        $this->appCustomerParam($params);

        return InvoiceItem::create($params, $this->opts);
    }

    /**
     * Get all invoices
     *
     * @param  array $params
     *
     * @return Collection|array
     */
    public function invoices($params = [])
    {
        $this->appCustomerParam($params);

        return Invoice::all($params, $this->opts);
    }

    /**
     * Get all invoice items
     *
     * @param  array $params
     *
     * @return Collection|array
     */
    public function invoiceItems($params = [])
    {
        $this->appCustomerParam($params);

        return InvoiceItem::all($params, $this->opts);
    }

    /**
     * Get all charges
     *
     * @param  array $params
     *
     * @return Collection|array
     */
    public function charges($params = [])
    {
        $this->appCustomerParam($params);

        return Charge::all($params, $this->opts);
    }

    /**
     * Update a Subscription
     *
     * @param  array|null $params
     *
     * @return Subscription
     */
    public function updateSubscription($params = [])
    {
        list($response, $opts) = $this->request('post', $this->getSubscriptionUrl(), $params);
        $this->refreshFrom(['subscription' => $response], $opts, true);

        return $this->subscription;
    }

    /**
     * Cancel Subscription
     *
     * @param  array|null $params
     *
     * @return Subscription
     */
    public function cancelSubscription($params = [])
    {
        list($response, $opts) = $this->request('delete', $this->getSubscriptionUrl(), $params);
        $this->refreshFrom(['subscription' => $response], $opts, true);

        return $this->subscription;
    }

    /**
     * Delete Discount
     *
     * @return Object
     */
    public function deleteDiscount()
    {
        list($response, $opts) = $this->request('delete', $this->getDiscountUrl());
        unset($response);
        $this->refreshFrom(['discount' => null], $opts, true);

        return $this;
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
        if (! $params) {
            $params = [];
        }
        $params['customer'] = $this->id;
    }
}
