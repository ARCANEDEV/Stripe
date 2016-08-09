<?php namespace Arcanedev\Stripe\Exceptions;

/**
 * Class     CardException
 *
 * @package  Arcanedev\Stripe\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CardException extends StripeException
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $declineCode;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * CardException constructor.
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
        $code,
        $type,
        $stripeCode,
        $httpBody,
        array $jsonBody,
        array $params = [],
        array $headers = []
    ) {
        parent::__construct(
            $message, $code, $type, $stripeCode, $httpBody, $jsonBody, $params, $headers
        );

        // This one is not like the others because it was added later and we're trying to do our best
        // not to change the public interface of this class' constructor.
        // We should consider changing its implementation on the next major version bump of this library.
        $this->setDeclineCode(
            isset($jsonBody['error']['decline_code']) ? $jsonBody['error']['decline_code'] : null
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    public function getDeclineCode()
    {
        return $this->declineCode;
    }

    private function setDeclineCode($declineCode)
    {
        $this->declineCode = $declineCode;

        return $this;
    }
}
