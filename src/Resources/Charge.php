<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ChargeInterface;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * @property string     id
 * @property mixed|null dispute
 * @property mixed|null refunds
 * @property mixed|null metadata
 * @property mixed|null refunded
 * @property mixed|null paid
 */
class Charge extends Resource implements ChargeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get an array of Stripe Charges.
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * Retrieve one Stripe Charge
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
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return Charge The created charge.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * @return Charge The saved charge.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * @param array|null $params
     *
     * @return Charge The refunded charge.
     */
    public function refund($params = null)
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/refund', $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * @param array|null $params
     *
     * @return Charge The captured charge.
     */
    public function capture($params = null)
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/capture', $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * @param array|null $params
     *
     * @return array The updated dispute.
     */
    public function updateDispute($params = null)
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/dispute', $params);

        $this->refreshFrom(['dispute' => $response], $apiKey, true);

        return $this->dispute;
    }

    /**
     * @return Charge The updated charge.
     */
    public function closeDispute()
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/dispute/close');

        $this->refreshFrom($response, $apiKey);

        return $this;
    }

    /**
     * @return Charge The updated charge.
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
     * @return Charge The updated charge.
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
