<?php namespace Arcanedev\Stripe\Exceptions;

class ApiException extends StripeException
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct(
        $message,
        $statusCode = 500,
        $type = 'api_error',
        $stripeCode = null,
        $httpBody = null,
        $jsonBody = [],
        $params = []
    ) {
        parent::__construct(
            $message,
            $statusCode,
            $type,
            $stripeCode,
            $httpBody,
            $jsonBody,
            $params
        );
    }
}
