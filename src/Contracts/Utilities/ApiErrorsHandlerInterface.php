<?php namespace Arcanedev\Stripe\Contracts\Utilities;

use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\AuthenticationException;
use Arcanedev\Stripe\Exceptions\CardException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Exceptions\RateLimitException;

interface ApiErrorsHandlerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handle API Errors
     *
     * @param string $respBody
     * @param int    $respCode
     * @param array  $response
     *
     * @throws ApiException
     * @throws AuthenticationException
     * @throws CardException
     * @throws InvalidRequestException
     * @throws RateLimitException
     */
    public function handle($respBody, $respCode, $response);
}