<?php namespace Arcanedev\Stripe\Contracts\Utilities;

/**
 * Interface  ApiErrorsHandler
 *
 * @package   Arcanedev\Stripe\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ApiErrorsHandler
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handle API Errors.
     *
     * @param  string  $respBody
     * @param  int     $respCode
     * @param  array   $respHeaders
     * @param  array   $response
     */
    public function handle($respBody, $respCode, $respHeaders, $response);
}
