<?php namespace Arcanedev\Stripe\Contracts\Utilities;

interface UtilSetInterface
{
    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function includes($attribute);
}
