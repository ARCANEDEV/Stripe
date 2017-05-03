<?php namespace Arcanedev\Stripe\Exceptions;

/**
 * Class     SignatureVerificationException
 *
 * @package  Arcanedev\Stripe\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SignatureVerificationException extends StripeException
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string */
    protected $signatureHeader;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * SignatureVerificationException constructor.
     *
     * @param  string  $message
     * @param  string  $signatureHeader
     * @param  null    $httpBody
     */
    public function __construct($message, $signatureHeader, $httpBody = null)
    {
        parent::__construct($message, null, $httpBody, null, null);

        $this->signatureHeader = $signatureHeader;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the signature header.
     *
     * @return string
     */
    public function getSignatureHeader()
    {
        return $this->signatureHeader;
    }
}
