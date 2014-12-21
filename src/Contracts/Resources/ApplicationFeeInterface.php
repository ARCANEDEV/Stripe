<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface ApplicationFeeInterface
{
    /**
     * @param string      $id The ID of the application fee to retrieve.
     * @param string|null $apiKey
     *
     * @return ApplicationFeeInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * Get an array of application fees.
     *
     * @param string|null $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface|array
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param string|null $params
     *
     * @return ApplicationFeeInterface The refunded application fee.
     */
    public function refund($params = null);
}
