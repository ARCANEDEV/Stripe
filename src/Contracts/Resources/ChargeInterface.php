<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\Charge;

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
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject
     */
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Charge
     * @link https://stripe.com/docs/api/php#retrieve_charge
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a new charge (charging a credit card)
     * @link https://stripe.com/docs/api/php#create_charge
     *
     * @param  array       $params
     * @param  array|string|null $options
     *
     * @return Charge
     */
    public static function create($params = [], $options = null);

    /**
     * Save/Update a Charge
     * @link https://stripe.com/docs/api/php#update_charge
     *
     * @return Charge
     */
    public function save();

    /**
     * Creating a new refund
     * @link https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null  $params
     * @param  string|null $options
     *
     * @return Charge
     */
    public function refund($params = [], $options = null);

    /**
     * Capture a charge
     * @link https://stripe.com/docs/api/php#capture_charge
     *
     * @param  array|null $params
     *
     * @return Charge
     */
    public function capture($params = []);

    /**
     * Updating a dispute
     * @link https://stripe.com/docs/api/php#update_dispute
     *
     * @param  array|null $params
     *
     * @return Object
     */
    public function updateDispute($params = []);

    /**
     * Closing a dispute
     * @link https://stripe.com/docs/api/php#close_dispute
     *
     * @return Object
     */
    public function closeDispute();

    /**
     * Mark charge as Fraudulent
     *
     * @return Charge
     */
    public function markAsFraudulent();

    /**
     * Mark charge as Safe
     *
     * @return Charge
     */
    public function markAsSafe();
}
