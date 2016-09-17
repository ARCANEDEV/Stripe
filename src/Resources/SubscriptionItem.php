<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\SubscriptionItemInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     SubscriptionItem
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string                            id
 * @property  string                            object   // 'subscription_item'
 * @property  int                               created
 * @property  \Arcanedev\Stripe\Resources\Plan  plan
 * @property  int                               quantity
 */
class SubscriptionItem extends StripeResource implements SubscriptionItemInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
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
        return 'subscription_item';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Subscription items.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Subscription item.
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create a Subscription item.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update a Subscription item.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = null, $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Save a Subscription item.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }

    /**
     * Delete a Subscription item.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null)
    {
        return $this->scopedDelete($params, $options);
    }
}
