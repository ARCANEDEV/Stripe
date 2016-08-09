<?php namespace Arcanedev\Stripe\Exceptions;

/**
 * Class     ApiKeyNotSetException
 *
 * @package  Arcanedev\Stripe\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApiKeyNotSetException extends AuthenticationException
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * API Key Not Set Constructor.
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
        $message = 'No API key provided.  (HINT: set your API key using '
            . '"Stripe::setApiKey(<API-KEY>)".  You can generate API keys from '
            . 'the Stripe web interface.  See https://stripe.com/api for '
            . 'details, or email support@stripe.com if you have any questions.';

        parent::__construct(
            $message, $code, $type, $stripeCode, $httpBody, $jsonBody, $params, $headers
        );
    }
}
