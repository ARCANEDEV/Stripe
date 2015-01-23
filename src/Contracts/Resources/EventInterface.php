<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\Event;

interface EventInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an event
     * @link https://stripe.com/docs/api/php#retrieve_event
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Event
     */
    public static function retrieve($id, $options = null);

    /**
     * List all events
     * @link https://stripe.com/docs/api/php#list_events
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject|array
     */
    public static function all($params = [], $options = null);
}
