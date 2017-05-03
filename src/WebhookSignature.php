<?php namespace Arcanedev\Stripe;

/**
 * Class     WebhookSignature
 *
 * @package  Arcanedev\Stripe
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class WebhookSignature
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const EXPECTED_SCHEME = 'v1';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Verifies the signature header sent by Stripe.
     * Throws a SignatureVerification exception if the verification fails for any reason.
     *
     * @param  string  $payload    The payload sent by Stripe.
     * @param  string  $header     The contents of the signature header sent by Stripe.
     * @param  string  $secret     Secret used to generate the signature.
     * @param  int     $tolerance  Maximum difference allowed between the header's timestamp and the current time
     *
     * @return bool
     *
     * @throws \Arcanedev\Stripe\Exceptions\SignatureVerificationException
     */
    public static function verifyHeader($payload, $header, $secret, $tolerance = null)
    {
        // Extract timestamp and signatures from header
        $timestamp  = self::getTimestamp($header);
        $signatures = self::getSignatures($header, self::EXPECTED_SCHEME);

        if ($timestamp == -1) {
            throw new Exceptions\SignatureVerificationException(
                'Unable to extract timestamp and signatures from header',
                    $header,
                    $payload
            );
        }

        if (empty($signatures)) {
            throw new Exceptions\SignatureVerificationException(
                "No signatures found with expected scheme", $header, $payload
            );
        }

        // Check if expected signature is found in list of signatures from header
        $signedPayload     = "$timestamp.$payload";
        $expectedSignature = self::computeSignature($signedPayload, $secret);
        $signatureFound    = false;

        foreach ($signatures as $signature) {
            if (Utilities\Util::secureCompare($expectedSignature, $signature)) {
                $signatureFound = true;
                break;
            }
        }

        if ( ! $signatureFound) {
            throw new Exceptions\SignatureVerificationException(
                'No signatures found matching the expected signature for payload',
                    $header,
                    $payload
            );
        }

        // Check if timestamp is within tolerance
        if (($tolerance > 0) && ((time() - $timestamp) > $tolerance)) {
            throw new Exceptions\SignatureVerificationException(
                'Timestamp outside the tolerance zone',
                    $header,
                    $payload
            );
        }

        return true;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Extracts the timestamp in a signature header.
     *
     * @param string $header the signature header
     * @return int the timestamp contained in the header, or -1 if no valid timestamp is found
     */
    private static function getTimestamp($header)
    {
        $items = explode(',', $header);

        foreach ($items as $item) {
            $itemParts = explode('=', $item, 2);

            if ($itemParts[0] == 't') {
                if ( ! is_numeric($itemParts[1])) return -1;

                return intval($itemParts[1]);
            }
        }

        return -1;
    }

    /**
     * Extracts the signatures matching a given scheme in a signature header.
     *
     * @param  string  $header  The signature header
     * @param  string  $scheme  The signature scheme to look for.
     *
     * @return array   The list of signatures matching the provided scheme.
     */
    private static function getSignatures($header, $scheme)
    {
        $signatures = [];
        $items      = explode(',', $header);

        foreach ($items as $item) {
            $itemParts = explode('=', $item, 2);

            if ($itemParts[0] == $scheme) {
                array_push($signatures, $itemParts[1]);
            }
        }

        return $signatures;
    }

    /**
     * Computes the signature for a given payload and secret.
     *
     * The current scheme used by Stripe ("v1") is HMAC/SHA-256.
     *
     * @param  string  $payload  The payload to sign.
     * @param  string  $secret   The secret used to generate the signature.
     *
     * @return string  The signature as a string.
     */
    private static function computeSignature($payload, $secret)
    {
        return hash_hmac('sha256', $payload, $secret);
    }
}
