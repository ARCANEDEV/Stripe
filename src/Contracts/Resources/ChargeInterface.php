<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  ChargeInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ChargeInterface
{
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
    public static function all($params = [], $options = null);

    /**
     * Retrieve a Charge.
     * @link   https://stripe.com/docs/api/php#retrieve_charge
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * Create a new charge (charging a credit card).
     * @link   https://stripe.com/docs/api/php#create_charge
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Save/Update a Charge.
     * @link   https://stripe.com/docs/api/php#update_charge
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

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
    public static function update($id, $params = [], $options = null);

    /**
     * Creating a new refund
     * @link   https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null   $params
     * @param  string|null  $options
     *
     * @return self
     */
    public function refund($params = [], $options = null);

    /**
     * Capture a charge.
     * @link   https://stripe.com/docs/api/php#capture_charge
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function capture($params = [], $options = null);

    /**
     * Mark charge as Fraudulent.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function markAsFraudulent($options = null);

    /**
     * Mark charge as Safe.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function markAsSafe($options = null);
}
