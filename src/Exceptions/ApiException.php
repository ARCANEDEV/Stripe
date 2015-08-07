<?php namespace Arcanedev\Stripe\Exceptions;

use Arcanedev\Stripe\Bases\StripeException;

/**
 * Class ApiException
 * @package Arcanedev\Stripe\Exceptions
 */
class ApiException extends StripeException
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * API Error Constructor
     *
     * @param string      $message
     * @param int         $code
     * @param string|null $type
     * @param string|null $stripeCode
     * @param string|null $httpBody
     * @param array       $jsonBody
     * @param array       $params
     */
    public function __construct(
        $message,
        $code = 500,
        $type = 'api_error',
        $stripeCode = null,
        $httpBody = null,
        $jsonBody = [],
        $params = []
    ) {
        parent::__construct(
            $message,
            $code,
            $type,
            $stripeCode,
            $httpBody,
            $jsonBody,
            $params
        );
    }
}
