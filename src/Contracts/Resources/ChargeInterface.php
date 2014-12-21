<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface ChargeInterface
{
    /**
     * Get an array of Stripe Charges.
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array
     */
    public static function all($params = null, $apiKey = null);

    /**
     * Retrieve one Stripe Charge
     *
     * @param string      $id     The ID of the charge to retrieve.
     * @param string|null $apiKey
     *
     * @return ChargeInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return ChargeInterface The created charge.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @return ChargeInterface The saved charge.
     */
    public function save();

    /**
     * @param array|null $params
     *
     * @return ChargeInterface The refunded charge.
     */
    public function refund($params = null);

    /**
     * @param array|null $params
     *
     * @return ChargeInterface The captured charge.
     */
    public function capture($params = null);

    /**
     * @param array|null $params
     *
     * @return array The updated dispute.
     */
    public function updateDispute($params = null);

    /**
     * @return ChargeInterface The updated charge.
     */
    public function closeDispute();

    /**
     * @return ChargeInterface The updated charge.
     */
    public function markAsFraudulent();

    /**
     * @return ChargeInterface The updated charge.
     */
    public function markAsSafe();
}
