<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\AttachedObjectInterface;

/**
 * Class     AttachedObject
 *
 * @package  Arcanedev\Stripe
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AttachedObject extends StripeObject implements AttachedObjectInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var bool
     */
    protected $checkUnsavedAttributes = false;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Updates this object.
     *
     * @param array $properties - A mapping of properties to update on this object.
     */
    public function replaceWith($properties)
    {
        $removed = array_diff(array_keys($this->values), array_keys($properties));

        // Don't unset, but rather set to null so we send up '' for deletion.
        foreach ($removed as $k) {
            $this->$k = null;
        }

        foreach ($properties as $k => $v) {
            $this->$k = $v;
        }
    }
}
