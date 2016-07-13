<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\PlanInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Plan
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#plan_object
 *
 * @property  string                            id
 * @property  string                            object                // 'plan'
 * @property  int                               amount
 * @property  int                               created               // timestamp
 * @property  int                               currency
 * @property  string                            interval              // 'day', 'week', 'month' or 'year'
 * @property  int                               interval_count
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            name
 * @property  int                               trial_period_days
 * @property  string                            statement_descriptor
 */
class Plan extends StripeResource implements PlanInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Plans.
     * @link   https://stripe.com/docs/api/php#list_plans
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Plan.
     * @link   https://stripe.com/docs/api/php#retrieve_plan
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
     * Create a Plan.
     * @link   https://stripe.com/docs/api/php#create_plan
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update a Plan.
     * @link   https://stripe.com/docs/api/php#update_plan
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = [], $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Update/Save a Plan.
     * @link   https://stripe.com/docs/api/php#update_plan
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }

    /**
     * Delete a plan.
     * @link   https://stripe.com/docs/api/php#delete_plan
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null)
    {
        return self::scopedDelete($params, $options);
    }
}
