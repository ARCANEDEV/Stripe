<?php namespace Arcanedev\Stripe\Contracts\Utilities;

interface CurlRequestInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    public function setOption($name, $value);

    public function getInfo($name);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function execute();

    public function close();
}
