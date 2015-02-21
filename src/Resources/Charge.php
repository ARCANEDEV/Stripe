<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\ChargeInterface;
use Arcanedev\Stripe\Collection;
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
 * @property Collection     refunds
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
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return parent::scopedAll($params, $options);
    }

    /**
     * Retrieve a Charge
     * @link https://stripe.com/docs/api/php#retrieve_charge
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public static function retrieve($id, $options = null)
    {
        return parent::scopedRetrieve($id, $options);
    }

    /**
     * Create a new charge (charging a credit card)
     * @link https://stripe.com/docs/api/php#create_charge
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @return Charge|array
     */
    public static function create($params = [], $options = null)
    {
        return parent::scopedCreate($params, $options);
    }

    /**
     * Save/Update a Charge
     * @link https://stripe.com/docs/api/php#update_charge
     *
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public function save($options = null)
    {
        return parent::scopedSave($options);
    }

    /**
     * Creating a new refund
     * @link https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public function refund($params = [], $options = null)
    {
        $url = $this->instanceUrl() . '/refund';

        return parent::scopedPostCall($url, $params, $options);
    }

    /**
     * Capture a charge
     * @link https://stripe.com/docs/api/php#capture_charge
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public function capture($params = [], $options = null)
    {
        $url = $this->instanceUrl() . '/capture';

        return parent::scopedPostCall($url, $params, $options);
    }

    /**
     * Updating a dispute
     * @link https://stripe.com/docs/api/php#update_dispute
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Object
     */
    public function updateDispute($params = [], $options = null)
    {
        $url = $this->instanceUrl() . '/dispute';
        // TODO: Refactor to Requestor::make()
        list($response, $opts) = $this->request('post', $url, $params, $options);

        $this->refreshFrom(['dispute' => $response], $opts, true);

        return $this->dispute;
    }

    /**
     * Closing a dispute
     * @link https://stripe.com/docs/api/php#close_dispute
     *
     * @param  array|string|null $options
     *
     * @return Object
     */
    public function closeDispute($options = null)
    {
        $url = $this->instanceUrl() . '/dispute/close';

        return parent::scopedPostCall($url, [], $options);
    }

    /**
     * Mark charge as Fraudulent
     *
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public function markAsFraudulent($options = null)
    {
        return $this->updateFraudDetails(false);
    }

    /**
     * Mark charge as Safe
     *
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public function markAsSafe($options = null)
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
     * @param  bool $safe
     * @param  array|string|null $options
     *
     * @return $this
     */
    private function updateFraudDetails($safe = false, $options = null)
    {
        $fraud_details = [
            'fraud_details' => [
                'user_report' => $safe ? self::SAFE : self::FRAUDULENT,
            ],
        ];

        // TODO: Refactor to Requestor::make()
        list($response, $opts) = $this->request('post', $this->instanceUrl(), $fraud_details, $options);

        $this->refreshFrom($response, $opts);

        return $this;
    }
}
