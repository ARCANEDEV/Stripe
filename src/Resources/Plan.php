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
 * @property  string                            object                // "plan"
 * @property  bool                              livemode
 * @property  int                               amount
 * @property  int                               created
 * @property  int                               currency
 * @property  string                            interval
 * @property  int                               interval_count
 * @property  string                            name
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  int                               trial_period_days
 * @property  string                            statement_descriptor
 *
 * @todo:     Update the properties.
 */
class Plan extends StripeResource implements PlanInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Plan.
     *
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
     * List all Plans.
     *
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
     * Create a plan.
     *
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
     * Update/Save a plan.
     *
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
     *
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
