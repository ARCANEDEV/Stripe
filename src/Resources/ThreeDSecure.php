<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ThreeDSecureInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     ThreeDSecure
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  id
 * @property  string  object  // 'three_d_secure'
 */
class ThreeDSecure extends StripeResource implements ThreeDSecureInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getter and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the endpoint URL for the given class.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return '/v1/3d_secure';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create the 3D Secure object.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }
}
