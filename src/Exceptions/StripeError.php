<?php namespace Arcanedev\Stripe\Exceptions;

class StripeError extends \Exception
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string
     */
    protected $type;

    protected $stripeCode;

    protected $httpBody;

    protected $jsonBody;

    /** @var array */
    protected $params = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct(
        $message, $statusCode = 0, $type = null, $stripeCode = null, $httpBody = null, $jsonBody = [], $params = []
    )
    {
        parent::__construct($message, $statusCode);

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    private function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getStripeCode()
    {
        return $this->stripeCode;
    }

    /**
     * @param string|null $stripeCode
     */
    private function setStripeCode($stripeCode)
    {
        $this->stripeCode = $stripeCode;
    }

    /**
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->getCode();
    }

    /**
     * @return string|null
     */
    public function getHttpBody()
    {
        return $this->httpBody;
    }

    /**
     * @param null $httpBody
     */
    private function setHttpBody($httpBody)
    {
        $this->httpBody = $httpBody;
    }

    /**
     * @return array|null
     */
    public function getJsonBody()
    {
        return $this->jsonBody;
    }

    /**
     * @param array|null $jsonBody
     */
    private function setJsonBody($jsonBody)
    {
        $this->jsonBody = $jsonBody;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    private function setParams($params)
    {
        $this->params = $params;
    }
}
