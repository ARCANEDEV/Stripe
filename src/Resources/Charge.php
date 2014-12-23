<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\ChargeInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * Charge Object
 * @link https://stripe.com/docs/api/php#charges
 *
 * @property string         id
 * @property string         object  // "charge"
 * @property bool           livemode
 * @property int            amount
 * @property bool           captured
 * @property int            created
 * @property string         currency
 * @property bool           paid
 * @property bool           refunded
 * @property ListObject     refunds
 * @property int            amount_refunded
 * @property string         balance_transaction
 * @property Card           card
 * @property string         customer
 * @property string         description
 * @property Object         dispute
 * @property string         failure_code
 * @property string         failure_message
 * @property AttachedObject metadata
 * @property string         receipt_email
 * @property string         receipt_number
 * @property mixed          fraud_details
 * @property array          shipping
 */
class Charge extends Resource implements ChargeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Charges
     * @link https://stripe.com/docs/api/php#list_charges
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * Retrieve a Charge
     * @link https://stripe.com/docs/api/php#retrieve_charge
     *
     * @param string      $id     The ID of the charge to retrieve.
     * @param string|null $apiKey
     *
     * @return Charge
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * Create a new charge (charging a credit card)
     * @link https://stripe.com/docs/api/php#create_charge
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Charge
     */
    public static function create($params = [], $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * Save/Update a Charge
     * @link https://stripe.com/docs/api/php#update_charge
     *
     * @return Charge
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * Creating a new refund
     * @link https://stripe.com/docs/api/php#create_refund
     *
     * @param array $params
     *
     * @return Charge
     */
    public function refund($params = [])
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/refund', $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Capture a charge
     * @link https://stripe.com/docs/api/php#capture_charge
     *
     * @param array $params
     *
     * @return Charge
     */
    public function capture($params = [])
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/capture', $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Updating a dispute
     * @link https://stripe.com/docs/api/php#update_dispute
     *
     * @param array $params
     *
     * @return Object
     */
    public function updateDispute($params = [])
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/dispute', $params);

        $this->refreshFrom(['dispute' => $response], $apiKey, true);

        return $this->dispute;
    }

    /**
     * Closing a dispute
     * @link https://stripe.com/docs/api/php#close_dispute
     *
     * @return Object
     */
    public function closeDispute()
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/dispute/close');

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Mark charge as Fraudulent
     *
     * @return Charge
     */
    public function markAsFraudulent()
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl(), [
                'fraud_details' => [
                    'user_report' => 'fraudulent',
                ],
            ]);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * Mark charge as Safe
     *
     * @return Charge
     */
    public function markAsSafe()
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl(), [
                'fraud_details' => [
                    'user_report' => 'safe',
                ],
            ]);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }
}
