<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\EphemeralKey as EphemeralKeyContract;
use Arcanedev\Stripe\StripeResource;
use InvalidArgumentException;

/**
 * Class     EphemeralKey
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  $id
 * @property  string  $object
 * @property  int     $created
 * @property  int     $expires
 * @property  bool    $livemode
 * @property  string  $secret
 * @property  array   $associated_objects
 */
class EphemeralKey extends StripeResource implements EphemeralKeyContract
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get The name of the class, with namespacing and underscores stripped.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function className($class = '')
    {
        return 'ephemeral_key';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create the Ephemeral Key.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return self
     */
    public static function create($params = [], $options = null)
    {
        if ( ! $options['stripe_version']) {
            throw new InvalidArgumentException(
                'The `stripe_version` must be specified to create an ephemeral key'
            );
        }

        return self::scopedCreate($params, $options);
    }

    /**
     * Delete the Ephemeral Key.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null)
    {
        return $this->scopedDelete($params, $options);
    }
}
