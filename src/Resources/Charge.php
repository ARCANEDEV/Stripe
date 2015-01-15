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
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const SAFE       = 'safe';
    const FRAUDULENT = 'fraudulent';

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Charges
     * @link https://stripe.com/docs/api/php#list_charges
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return ListObject
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Charge
     * @link https://stripe.com/docs/api/php#retrieve_charge
     *
     * @param string            $id
     * @param array|string|null $options
     *
     * @return Charge
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create a new charge (charging a credit card)
     * @link https://stripe.com/docs/api/php#create_charge
     *
     * @param array       $params
     * @param array|string|null $options
     *
     * @return Charge
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Save/Update a Charge
     * @link https://stripe.com/docs/api/php#update_charge
     *
     * @return Charge
     */
    public function save()
    {
        return self::scopedSave();
    }

    /**
     * Creating a new refund
     * @link https://stripe.com/docs/api/php#create_refund
     *
     * @param array       $params
     * @param string|null $options
     *
     * @return Charge
     */
    public function refund($params = [], $options = null)
    {
        $url  = $this->instanceUrl() . '/refund';

        return parent::scopedPostCall($url, $params, $options);
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
        $url = $this->instanceUrl() . '/capture';

        return parent::scopedPostCall($url, $params);
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
        $url = $this->instanceUrl() . '/dispute';

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($url, $params);

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
        $url = $this->instanceUrl() . '/dispute/close';

        return parent::scopedPostCall($url);
    }

    /**
     * Mark charge as Fraudulent
     *
     * @return Charge
     */
    public function markAsFraudulent()
    {
        return $this->updateFraudDetails(false);
    }

    /**
     * Mark charge as Safe
     *
     * @return Charge
     */
    public function markAsSafe()
    {
        return $this->updateFraudDetails(true);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update charge's fraud details
     *
     * @param bool $safe
     *
     * @return $this
     */
    private function updateFraudDetails($safe = false)
    {
        $fraud_details = [
            'fraud_details' => [
                'user_report' => $safe ? self::SAFE : self::FRAUDULENT,
            ],
        ];

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl(), $fraud_details);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }
}
