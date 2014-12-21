<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface EventInterface
{
    /**
     * @param string      $id The ID of the event to retrieve.
     * @param string|null $apiKey
     *
     * @return EventInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * An array of Stripe Events.
     *
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array
     */
    public static function all($params = null, $apiKey = null);
}
