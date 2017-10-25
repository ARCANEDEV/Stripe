<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Resources\Event;
use UnexpectedValueException;

/**
 * Class     Webhook
 *
 * @package  Arcanedev\Stripe
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Webhook
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const DEFAULT_TOLERANCE = 300;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Returns an Event instance using the provided JSON payload.
     *
     * Throws a `UnexpectedValueException` if the payload is not valid JSON,
     * and a `SignatureVerificationException` if the signature verification fails for any reason.
     *
     * @param  string  $payload    The payload sent by Stripe.
     * @param  string  $sigHeader  The contents of the signature header sent by Stripe.
     * @param  string  $secret     Secret used to generate the signature.
     * @param  int     $tolerance  Maximum difference allowed between the header's timestamp and the current time
     *
     * @return \Arcanedev\Stripe\Resources\Event  the Event instance
     *
     * @throws UnexpectedValueException
     */
    public static function constructEvent($payload, $sigHeader, $secret, $tolerance = self::DEFAULT_TOLERANCE)
    {
        $data      = json_decode($payload, true);
        $jsonError = json_last_error();

        if ($data === null && $jsonError !== JSON_ERROR_NONE) {
            throw new UnexpectedValueException(
                "Invalid payload: $payload (json_last_error() was $jsonError)"
            );
        }

        /** @var  \Arcanedev\Stripe\Resources\Event  $event */
        $event = Event::scopedConstructFrom($data, null);

        WebhookSignature::verifyHeader($payload, $sigHeader, $secret, $tolerance);

        return $event;
    }
}
