<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Event Object Interface
 * @link https://stripe.com/docs/api/php#event_object
 * @link https://stripe.com/docs/api/php#event_types
 *
 * @property string id
 * @property string object // "event"
 * @property bool   livemode
 * @property int    created
 * @property array  data
 * @property int    pending_webhooks
 * @property string type
 * @property string api_version
 * @property string request
 * @property string customer_email
 */
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
     * @param string            $id
     * @param array|string|null $options
     *
     * @return EventInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * List all events
     * @link https://stripe.com/docs/api/php#list_events
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $options = null);
}
