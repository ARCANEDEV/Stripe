<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ChargeInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Charge
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#charge_object
 *
 * @property  string                               id
 * @property  string                               object               // 'charge'
 * @property  int                                  amount
 * @property  int                                  amount_refunded
 * @property  string|null                          application_fee
 * @property  string                               balance_transaction
 * @property  bool                                 captured
 * @property  int                                  created              // timestamp
 * @property  string                               currency
 * @property  string                               customer
 * @property  string                               description
 * @property  string                               destination
 * @property  \Arcanedev\Stripe\Resources\Dispute  dispute
 * @property  string                               failure_code
 * @property  string                               failure_message
 * @property  array                                fraud_details
 * @property  string                               invoice
 * @property  bool                                 livemode
 * @property  \Arcanedev\Stripe\AttachedObject     metadata
 * @property  string                               order
 * @property  bool                                 paid
 * @property  string                               receipt_email
 * @property  string                               receipt_number
 * @property  bool                                 refunded
 * @property  \Arcanedev\Stripe\Collection         refunds
 * @property  array                                shipping
 * @property  \Arcanedev\Stripe\Resources\Card     source
 * @property  string                               source_transfer
 * @property  string                               statement_descriptor
 * @property  string                               status
 * @property  string                               transfer
 */
class Charge extends StripeResource implements ChargeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const SAFE       = 'safe';
    const FRAUDULENT = 'fraudulent';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Charges.
     * @link   https://stripe.com/docs/api/php#list_charges
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
     * Retrieve a Charge.
     * @link   https://stripe.com/docs/api/php#retrieve_charge
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
     * Create a new charge (charging a credit card).
     * @link   https://stripe.com/docs/api/php#create_charge
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Save/Update a Charge.
     * @link   https://stripe.com/docs/api/php#update_charge
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
     * Update a Charge.
     * @link   https://stripe.com/docs/api/php#update_charge
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
     * Creating a new refund.
     * @link   https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function refund($params = [], $options = null)
    {
        return self::scopedPostCall(
            $this->instanceUrl() . '/refund', $params, $options
        );
    }

    /**
     * Capture a charge.
     * @link   https://stripe.com/docs/api/php#capture_charge
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function capture($params = [], $options = null)
    {
        return self::scopedPostCall(
            $this->instanceUrl() . '/capture', $params, $options
        );
    }

    /**
     * Mark charge as Fraudulent.
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function markAsFraudulent($options = null)
    {
        return $this->updateFraudDetails(false);
    }

    /**
     * Mark charge as Safe.
     * @param  array|string|null  $options
     *
     * @return self
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
     * Update charge's fraud details.
     *
     * @param  bool               $safe
     * @param  array|string|null  $options
     *
     * @return self
     */
    private function updateFraudDetails($safe = false, $options = null)
    {
        $params = [
            'fraud_details' => [
                'user_report' => $safe ? self::SAFE : self::FRAUDULENT,
            ],
        ];

        return $this->scopedPostCall($this->instanceUrl(), $params, $options);
    }
}
