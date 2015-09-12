<?php namespace Arcanedev\Stripe\Bases;

/**
 * Class     StripeException
 *
 * @package  Arcanedev\Stripe\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class StripeException extends \Exception
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Stripe Error type.
     *
     * @var string|null
     */
    protected $type;

    /**
     * Stripe Error code.
     *
     * @var string|null
     */
    protected $stripeCode;

    /**
     * HTTP Response Body (json).
     *
     * @var string|null
     */
    protected $httpBody;

    /**
     * HTTP Response Body (array).
     *
     * @var array|null
     */
    protected $jsonBody;

    /**
     * Parameters.
     *
     * @var array
     */
    protected $params = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Stripe Exception instance.
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
        $code = 0,
        $type = null,
        $stripeCode = null,
        $httpBody = null,
        $jsonBody = [],
        $params = []
    ) {
        parent::__construct($message, $code);

        // Stripe Properties
        //--------------------------------------
        $this->setType($type);
        $this->setStripeCode($stripeCode);
        $this->setHttpBody($httpBody);
        $this->setJsonBody($jsonBody);
        $this->setParams($params);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Stripe Error type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Type.
     *
     * @param  string|null  $type
     *
     * @return self
     */
    private function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Stripe code.
     *
     * @return string|null
     */
    public function getStripeCode()
    {
        return $this->stripeCode;
    }

    /**
     * Set Stripe code.
     *
     * @param  string|null  $stripeCode
     *
     * @return self
     */
    private function setStripeCode($stripeCode)
    {
        $this->stripeCode = $stripeCode;

        return $this;
    }

    /**
     * Get HTTP status code.
     *
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->getCode();
    }

    /**
     * Get HTTP Body.
     *
     * @return string|null
     */
    public function getHttpBody()
    {
        return $this->httpBody;
    }

    /**
     * Set HTTP Body.
     *
     * @param  string|null  $httpBody
     *
     * @return self
     */
    private function setHttpBody($httpBody)
    {
        $this->httpBody = $httpBody;

        return $this;
    }

    /**
     * Get Json Body.
     *
     * @return array|null
     */
    public function getJsonBody()
    {
        return $this->jsonBody;
    }

    /**
     * Set Json Body.
     *
     * @param  array|null  $jsonBody
     *
     * @return self
     */
    private function setJsonBody($jsonBody)
    {
        $this->jsonBody = $jsonBody;

        return $this;
    }

    /**
     * Get Parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set parameters.
     *
     * @param  array  $params
     *
     * @return self
     */
    private function setParams($params)
    {
        $this->params = $params;

        return $this;
    }
}
