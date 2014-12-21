<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface TransferInterface
{
    /**
     * @param string      $id The ID of the transfer to retrieve.
     * @param string|null $apiKey
     *
     * @return TransferInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Transfers.
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return TransferInterface The created transfer.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @return TransferInterface The canceled transfer.
     */
    public function cancel();

    /**
     * @return TransferInterface The saved transfer.
     */
    public function save();
}
