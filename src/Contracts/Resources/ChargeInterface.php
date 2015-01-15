<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;
use Arcanedev\Stripe\Contracts\ObjectInterface;

/**
 * Charge Object Interface
 * @link https://stripe.com/docs/api/php#charges
 *
 * @property string                  id
 * @property string                  object  // "charge"
 * @property bool                    livemode
 * @property int                     amount
 * @property bool                    captured
 * @property int                     created
 * @property string                  currency
 * @property bool                    paid
 * @property bool                    refunded
 * @property ListObjectInterface     refunds
 * @property int                     amount_refunded
 * @property string                  balance_transaction
 * @property CardInterface           card
 * @property string                  customer
 * @property string                  description
 * @property Object                  dispute
 * @property string                  failure_code
 * @property string                  failure_message
 * @property AttachedObjectInterface metadata
 * @property string                  receipt_email
 * @property string                  receipt_number
 * @property mixed                   fraud_details
 * @property array                   shipping
 */
interface ChargeInterface
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
     * @param string|null $options
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Charge
     * @link https://stripe.com/docs/api/php#retrieve_charge
     *
     * @param string      $id     The ID of the charge to retrieve.
     * @param string|null $options
     *
     * @return ChargeInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a new charge (charging a credit card)
     * @link https://stripe.com/docs/api/php#create_charge
     *
     * @param array       $params
     * @param string|null $options
     *
     * @return ChargeInterface
     */
    public static function create($params = [], $options = null);

    /**
     * Save/Update a Charge
     * @link https://stripe.com/docs/api/php#update_charge
     *
     * @return ChargeInterface
     */
    public function save();

    /**
     * Creating a new refund
     * @link https://stripe.com/docs/api/php#create_refund
     *
     * @param array $params
     *
     * @return ChargeInterface
     */
    public function refund($params = []);

    /**
     * @param array|null $params
     *
     * @return ChargeInterface
     */
    public function capture($params = []);

    /**
     * Updating a dispute
     * @link https://stripe.com/docs/api/php#update_dispute
     *
     * @param array $params
     *
     * @return ObjectInterface
     */
    public function updateDispute($params = []);

    /**
     * Closing a dispute
     * @link https://stripe.com/docs/api/php#close_dispute
     *
     * @return ObjectInterface
     */
    public function closeDispute();

    /**
     * Mark charge as Fraudulent
     *
     * @return ChargeInterface
     */
    public function markAsFraudulent();

    /**
     * Mark charge as Safe
     *
     * @return ChargeInterface
     */
    public function markAsSafe();
}
