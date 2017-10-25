<?php namespace Arcanedev\Stripe\Exceptions\OAuth;

use Arcanedev\Stripe\Exceptions\StripeException;

/**
 * Class     OAuthException
 *
 * @package  Arcanedev\Stripe\Exceptions\OAuth
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class OAuthException extends StripeException
{
    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    public function __construct(
        $code, $message, $httpStatus = null, $httpBody = null, $jsonBody = null, $httpHeaders = null
    ) {
        parent::__construct($message, $httpStatus, $httpBody, $jsonBody, $httpHeaders);

        $this->code = $code;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    public function getErrorCode()
    {
        return $this->code;
    }
}
