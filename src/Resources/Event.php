<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Collection;
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
 * @property  string  id
 * @property  string  object            // "event"
 * @property  bool    livemode
 * @property  int     created
 * @property  array   data
 * @property  int     pending_webhooks
 * @property  string  type
 * @property  string  api_version
 * @property  string  request
 * @property  string  customer_email
 */
class Event extends StripeResource implements EventInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an event.
     *
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
     *
     * @link   https://stripe.com/docs/api/php#list_events
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
