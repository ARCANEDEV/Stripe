<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\EventInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Event
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#event_object
 * @link     https://stripe.com/docs/api/php#event_types
 *
 * @property  string                          id
 * @property  string                          object            // 'event'
 * @property  string                          api_version
 * @property  int                             created           // timestamp
 * @property  \Arcanedev\Stripe\StripeObject  data
 * @property  bool                            livemode
 * @property  int                             pending_webhooks
 * @property  string                          request
 * @property  string                          type
 */
class Event extends StripeResource implements EventInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an event.
     * @link   https://stripe.com/docs/api/php#retrieve_event
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * List all events.
     * @link   https://stripe.com/docs/api/php#list_events
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
