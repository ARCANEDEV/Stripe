<?php namespace Arcanedev\Stripe\Exceptions;

class ApiKeyNotSetException extends AuthenticationException
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * API Key Not Set Constructor
     *
     * @param string      $message
     * @param int         $statusCode
     * @param string|null $type
     * @param string|null $stripeCode
     * @param string|null $httpBody
     * @param array       $jsonBody
     * @param array       $params
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
        $message = 'No API key provided.  (HINT: set your API key using '
        . '"Stripe::setApiKey(<API-KEY>)".  You can generate API keys from '
        . 'the Stripe web interface.  See https://stripe.com/api for '
        . 'details, or email support@stripe.com if you have any questions.';

        parent::__construct($message, $statusCode, $type);
    }
}
