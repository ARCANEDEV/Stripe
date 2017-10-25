<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\Customer as CustomerContract;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Customer
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#customer_object
 *
 * @property  string                                         id
 * @property  string                                         object           // 'customer'
 * @property  int                                            account_balance
 * @property  int                                            created          // timestamp
 * @property  string                                         currency
 * @property  string                                         default_source
 * @property  bool                                           delinquent
 * @property  string                                         description
 * @property  \Arcanedev\Stripe\Resources\Discount|null      discount
 * @property  string                                         email
 * @property  bool                                           livemode
 * @property  \Arcanedev\Stripe\AttachedObject               metadata
 * @property  string                                         shipping
 * @property  \Arcanedev\Stripe\Collection                   sources
 * @property  \Arcanedev\Stripe\Collection                   subscriptions
 * @property  \Arcanedev\Stripe\Resources\Subscription|null  subscription    // It's for updateSubscription and cancelSubscription
 */
class Customer extends StripeResource implements CustomerContract
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const PATH_SOURCES = '/sources';

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Allow to check attributes while setting.
     *
     * @var bool
     */
    protected $checkUnsavedAttributes = true;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get Subscription URL.
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     *
     * @return string
     */
    private function getSubscriptionUrl()
    {
        return $this->instanceUrl().'/subscription';
    }

    /**
     * Get Discount URL.
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     *
     * @return string
     */
    private function getDiscountUrl()
    {
        return $this->instanceUrl().'/discount';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * List all Customers.
     * @link   https://stripe.com/docs/api/php#list_customers
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
     * Retrieve a Customer.
     * @link   https://stripe.com/docs/api/php#retrieve_customer
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
     * Create Customer.
     * @link   https://stripe.com/docs/api/php#create_customer
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
     * Update a Customer.
     * @link   https://stripe.com/docs/api/php#create_customer
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
     * Update/Save a Customer.
     * @link   https://stripe.com/docs/api/php#create_customer
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
     * Delete Customer.
     * @link   https://stripe.com/docs/api/php#delete_customer
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null)
    {
        return self::scopedDelete($params, $options);
    }

    /* -----------------------------------------------------------------
     |  Relationships Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add an invoice item.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Resources\InvoiceItem|array
     */
    public function addInvoiceItem($params = [])
    {
        $this->appCustomerParam($params);

        return InvoiceItem::create($params, $this->opts);
    }

    /**
     * Get all invoices.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public function invoices($params = [])
    {
        $this->appCustomerParam($params);

        return Invoice::all($params, $this->opts);
    }

    /**
     * Get all invoice items.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public function invoiceItems($params = [])
    {
        $this->appCustomerParam($params);

        return InvoiceItem::all($params, $this->opts);
    }

    /**
     * Get all charges.
     *
     * @param  array  $params
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public function charges($params = [])
    {
        $this->appCustomerParam($params);

        return Charge::all($params, $this->opts);
    }

    /**
     * Update a Subscription.
     *
     * @param  array|null  $params
     *
     * @return \Arcanedev\Stripe\Resources\Subscription
     */
    public function updateSubscription($params = [])
    {
        list($response, $opts) = $this->request('post', $this->getSubscriptionUrl(), $params);
        $this->refreshFrom(['subscription' => $response], $opts, true);

        return $this->subscription;
    }

    /**
     * Cancel Subscription.
     *
     * @param  array|null  $params
     *
     * @return \Arcanedev\Stripe\Resources\Subscription
     */
    public function cancelSubscription($params = [])
    {
        list($response, $opts) = $this->request('delete', $this->getSubscriptionUrl(), $params);
        $this->refreshFrom(['subscription' => $response], $opts, true);

        return $this->subscription;
    }

    /**
     * Delete Discount.
     *
     * @return \Arcanedev\Stripe\StripeObject
     */
    public function deleteDiscount()
    {
        list($response, $opts) = $this->request('delete', $this->getDiscountUrl());
        $this->refreshFrom(['discount' => null], $opts, true);

        unset($response);

        return $this;
    }

    /**
     * Create a source.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function createSource($id, $params = null, $options = null)
    {
        return self::createNestedResource($id, static::PATH_SOURCES, $params, $options);
    }

    /**
     * Retrieve a source.
     *
     * @param  string             $id
     * @param  string             $sourceId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function retrieveSource($id, $sourceId, $params = null, $options = null)
    {
        return self::retrieveNestedResource($id, static::PATH_SOURCES, $sourceId, $params, $options);
    }

    /**
     * Update a source.
     *
     * @param  string             $id
     * @param  string             $sourceId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function updateSource($id, $sourceId, $params = null, $options = null)
    {
        return self::updateNestedResource($id, static::PATH_SOURCES, $sourceId, $params, $options);
    }

    /**
     * Delete a source.
     *
     * @param  string             $id
     * @param  string             $sourceId
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Bases\ExternalAccount
     */
    public static function deleteSource($id, $sourceId, $params = null, $options = null)
    {
        return self::deleteNestedResource($id, static::PATH_SOURCES, $sourceId, $params, $options);
    }

    /**
     * List all the sources.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection
     */
    public static function allSources($id, $params = null, $options = null)
    {
        return self::allNestedResources($id, static::PATH_SOURCES, $params, $options);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add Customer ID to parameters.
     *
     * @param  array  $params
     */
    private function appCustomerParam(&$params)
    {
        if (empty($params)) $params = [];

        $params['customer'] = $this->id;
    }
}
