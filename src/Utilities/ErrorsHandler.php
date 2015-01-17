<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Contracts\Utilities\ApiErrorsHandlerInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\AuthenticationException;
use Arcanedev\Stripe\Exceptions\CardException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Exceptions\RateLimitException;

class ErrorsHandler implements ApiErrorsHandlerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    private $respBody;

    /** @var int */
    private $respCode;

    /** @var array */
    private $response   = [];

    /** @var array */
    private static $exceptions = [
        400 => '\\Arcanedev\\Stripe\\Exceptions\\InvalidRequestException',
        401 => '\\Arcanedev\\Stripe\\Exceptions\\AuthenticationException',
        402 => '\\Arcanedev\\Stripe\\Exceptions\\CardException',
        404 => '\\Arcanedev\\Stripe\\Exceptions\\InvalidRequestException',
        429 => '\\Arcanedev\\Stripe\\Exceptions\\RateLimitException',
        500 => '\\Arcanedev\\Stripe\\Exceptions\\ApiException',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set Response body (JSON)
     *
     * @param string $respBody
     *
     * @return ErrorsHandler
     */
    private function setRespBody($respBody)
    {
        $this->respBody = $respBody;

        return $this;
    }

    /**
     * Set Response Code
     *
     * @param int $respCode
     *
     * @return ErrorsHandler
     */
    private function setRespCode($respCode)
    {
        $this->respCode = $respCode;

        return $this;
    }

    /**
     * Set Response
     *
     * @param array $response
     *
     * @return $this
     */
    private function setResponse($response)
    {
        $this->checkResponse($response);

        $this->response = $response;

        return $this;
    }

    /**
     * Get Exception class
     *
     * @return string
     */
    private function getException()
    {
        return $this->hasException()
            ? $this->getExceptionByCode($this->respCode)
            : $this->getExceptionByCode(500);
    }

    /**
     * Get Exception class by status code
     *
     * @param int $code
     *
     * @return string
     */
    private function getExceptionByCode($code)
    {
        return self::$exceptions[$code];
    }

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
     *
     * @return void
     */
    public function handle($respBody, $respCode, $response)
    {
        if ($respCode >= 200 or $respCode < 300) {
            return;
        }

        $this->setRespBody($respBody);
        $this->setRespCode($respCode);
        $this->setResponse($response);

        list($message, $type, $stripeCode, $params) = $this->parseResponseError();

        $exception = $this->getException();

        if ($this->respCode === 400 and $stripeCode === 'rate_limit') {
            $this->setRespCode(429);
            $exception = $this->getExceptionByCode(429);
        }

        throw new $exception(
            $message,
            $this->respCode,
            $type,
            $stripeCode,
            $this->respBody,
            $this->response,
            $params
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has Response
     *
     * @param $response
     *
     * @return bool
     */
    private function hasErrorResponse($response)
    {
        return is_array($response) and isset($response['error']);
    }

    /**
     * Check Response
     *
     * @param mixed $response
     *
     * @throws ApiException
     */
    private function checkResponse($response)
    {
        if (! $this->hasErrorResponse($response)) {
            $msg = str_replace([
                '{resp-body}',
                '{resp-code}'
            ], [
                $this->respBody,
                $this->respCode,
            ], 'Invalid response object from API: {resp-body} (HTTP response code was {resp-code})');

            throw new ApiException($msg, $this->respCode, $this->respBody, $response);
        }
    }

    /**
     * Check if has Exception
     *
     * @return bool
     */
    private function hasException()
    {
        return array_key_exists($this->respCode, self::$exceptions);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Parse response error
     *
     * @return array
     */
    private function parseResponseError()
    {
        return [
            $this->getResponseError('message'),
            $this->getResponseError('type'),
            $this->getResponseError('code'),
            $this->getResponseError('param'),
        ];
    }

    /**
     * Get Error attribute
     *
     * @param string $name
     *
     * @return mixed
     */
    private function getResponseError($name)
    {
        $error = null;

        if (isset($this->response['error'][$name])) {
            $error   = $this->response['error'][$name];
        }

        if ($name === 'param' and is_null($error)) {
            $ignored = ['type', 'message', 'code'];
            $error   = array_diff_key($this->response['error'], array_flip($ignored));
        }

        return $error;
    }
}
