<?php namespace Arcanedev\Stripe\Utilities;

use Arcanedev\Stripe\Contracts\Utilities\CurlRequestInterface;

class CurlRequest implements CurlRequestInterface
{
    // TODO: Complete CurlRequest Implementation
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private $handle = null;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($url)
    {
        $this->handle = curl_init($url);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    public function setOption($name, $value)
    {
        curl_setopt($this->handle, $name, $value);
    }

    public function getInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function execute()
    {
        return curl_exec($this->handle);
    }

    public function close()
    {
        curl_close($this->handle);
    }
}
