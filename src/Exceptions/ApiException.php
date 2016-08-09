<?php namespace Arcanedev\Stripe\Exceptions;

/**
 * Class     ApiException
 *
 * @package  Arcanedev\Stripe\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApiException extends StripeException
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * API Exception Constructor.
     *
     * @param  string       $message
     * @param  int          $code
     * @param  string|null  $type
     * @param  string|null  $stripeCode
     * @param  string|null  $httpBody
     * @param  array        $jsonBody
     * @param  array        $params
     * @param  array        $headers
     */
    public function __construct(
        $message,
        $code = 500,
        $type = 'api_error',
        $stripeCode = null,
        $httpBody = null,
        $jsonBody = [],
        $params = [],
        $headers = []
    ) {
        parent::__construct(
            $message, $code, $type, $stripeCode, $httpBody, $jsonBody, $params, $headers
        );
    }
}
