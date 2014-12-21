<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface RecipientInterface
{
    /**
     * @param string      $id The ID of the recipient to retrieve.
     * @param string|null $apiKey
     *
     * @return RecipientInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_Recipients.
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return RecipientInterface The created recipient.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @return RecipientInterface The saved recipient.
     */
    public function save();

    /**
     * @param array|null $params
     *
     * @return RecipientInterface The deleted recipient.
     */
    public function delete($params = null);

    /**
     * Get an array of the recipient's Transfers.
     *
     * @param array|null $params
     *
     * @return array
     */
    public function transfers($params = null);
}
