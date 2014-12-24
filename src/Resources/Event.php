<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\EventInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resource;

/**
 * Event Object
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
class Event extends Resource implements EventInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an event
     * @link https://stripe.com/docs/api/php#retrieve_event
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Event
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve($id, $apiKey);
    }

    /**
     * List all events
     * @link https://stripe.com/docs/api/php#list_events
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        return self::scopedAll($params, $apiKey);
    }
}
