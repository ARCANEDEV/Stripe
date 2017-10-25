<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Contracts\Utilities\ApiErrorsHandler as ApiErrorsHandlerContract;
use Arcanedev\Stripe\Exceptions;

/**
 * Class     ErrorsHandler
 *
 * @package  Arcanedev\Stripe\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ErrorsHandler implements ApiErrorsHandlerContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string */
    private $respBody;

    /** @var int */
    private $respCode;

    /** @var array */
    protected $respHeaders;

    /** @var array */
    private $response   = [];

    /** @var array */
    private static $apiExceptions = [
        400 => Exceptions\InvalidRequestException::class,
        401 => Exceptions\AuthenticationException::class,
        402 => Exceptions\CardException::class,
        403 => Exceptions\PermissionException::class,
        404 => Exceptions\InvalidRequestException::class,
        429 => Exceptions\RateLimitException::class,
        500 => Exceptions\ApiException::class,
    ];

    /** @var array */
    private static $oauthExceptions = [
        'invalid_grant'             => Exceptions\OAuth\InvalidGrantException::class,
        'invalid_request'           => Exceptions\OAuth\InvalidRequestException::class,
        'invalid_scope'             => Exceptions\OAuth\InvalidScopeException::class,
        'unsupported_grant_type'    => Exceptions\OAuth\UnsupportedGrantTypeException::class,
        'unsupported_response_type' => Exceptions\OAuth\UnsupportedResponseTypeException::class,
    ];

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set Response body (JSON).
     *
     * @param  string  $respBody
     *
     * @return self
     */
    private function setRespBody($respBody)
    {
        $this->respBody = $respBody;

        return $this;
    }

    /**
     * Set Response Code.
     *
     * @param  int  $respCode
     *
     * @return self
     */
    private function setRespCode($respCode)
    {
        $this->respCode = $respCode;

        return $this;
    }

    /**
     * Set Response Headers.
     *
     * @param  array  $respHeaders
     *
     * @return self
     */
    private function setRespHeaders($respHeaders)
    {
        $this->respHeaders = $respHeaders;

        return $this;
    }

    /**
     * Set Response.
     *
     * @param  array  $response
     *
     * @return self
     */
    private function setResponse($response)
    {
        $this->checkResponse($response);

        $this->response = $response;

        return $this;
    }

    /**
     * Get Exception class by status code.
     *
     * @param  int  $code
     *
     * @return string
     */
    private function getApiExceptionByCode($code)
    {
        return self::$apiExceptions[$code];
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle API Errors.
     *
     * @param  string  $respBody
     * @param  int     $respCode
     * @param  array   $respHeaders
     * @param  array   $response
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     * @throws \Arcanedev\Stripe\Exceptions\AuthenticationException
     * @throws \Arcanedev\Stripe\Exceptions\CardException
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @throws \Arcanedev\Stripe\Exceptions\PermissionException
     * @throws \Arcanedev\Stripe\Exceptions\RateLimitException
     */
    public function handle($respBody, $respCode, $respHeaders, $response)
    {
        if ($respCode >= 200 && $respCode < 300)
            return;

        $this->setRespBody($respBody);
        $this->setRespCode($respCode);
        $this->setRespHeaders($respHeaders);
        $this->setResponse($response);

        $exception = is_string($this->response['error'])
            ? $this->getOAuthException()
            : $this->getAPIException();

        throw $exception;
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check Response.
     *
     * @param  mixed  $response
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     */
    private function checkResponse($response)
    {
        if ( ! $this->hasErrorResponse($response)) {
            $msg = str_replace([
                '{resp-body}',
                '{resp-code}'
            ], [
                $this->respBody,
                $this->respCode,
            ], 'Invalid response object from API: {resp-body} (HTTP response code was {resp-code})');

            throw new Exceptions\ApiException($msg, $this->respCode, $this->respBody, $response);
        }
    }

    /**
     * Check if has Response.
     *
     * @param  mixed  $response
     *
     * @return bool
     */
    private function hasErrorResponse($response)
    {
        return is_array($response) && isset($response['error']);
    }

    /**
     * Check if has Exception.
     *
     * @return bool
     */
    private function hasException()
    {
        return array_key_exists($this->respCode, self::$apiExceptions);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Parse response error.
     *
     * @return \Arcanedev\Stripe\Exceptions\StripeException
     */
    private function getAPIException()
    {
        $stripeCode = $this->getResponseError('code');
        $exception  = $this->getApiExceptionByCode(
            $this->hasException() ? $this->respCode : 500
        );

        if ($this->respCode === 400 && $stripeCode === 'rate_limit') {
            $this->setRespCode(429);
            $exception = $this->getApiExceptionByCode(429);
        }

        return new $exception(
            $this->getResponseError('message'),
            $this->respCode,
            $this->getResponseError('type'),
            $stripeCode,
            $this->respBody,
            $this->response,
            $this->getResponseError('param'),
            $this->respHeaders
        );
    }

    /**
     * Get the OAuth exception.
     *
     * @return \Arcanedev\Stripe\Exceptions\OAuth\OAuthException
     */
    private function getOAuthException()
    {
        $errorCode   = $this->response['error'];
        $description = $this->response['error_description'] ?: $errorCode;

        $exception = self::$oauthExceptions[$errorCode];

        return new $exception(
            $errorCode,
            $description,
            $this->respCode,
            $this->respBody,
            $this->response,
            $this->respHeaders
        );
    }

    /**
     * Get Error attribute.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    private function getResponseError($name)
    {
        $error = null;

        if (isset($this->response['error'][$name])) {
            $error   = $this->response['error'][$name];
        }

        if ($name === 'param' && is_null($error)) {
            $ignored = ['type', 'message', 'code'];
            $error   = array_diff_key($this->response['error'], array_flip($ignored));
        }

        return $error;
    }
}
