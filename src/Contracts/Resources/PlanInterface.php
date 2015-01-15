<?php namespace Arcanedev\Stripe\Contracts\Resources;
use Arcanedev\Stripe\Contracts\AttachedObjectInterface;
use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Plan Object Interface
 * @link https://stripe.com/docs/api/php#plan_object
 *
 * @property string                  id
 * @property string                  object // "plan"
 * @property bool                    livemode
 * @property int                     amount
 * @property int                     created
 * @property int                     currency
 * @property string                  interval
 * @property int                     interval_count
 * @property string                  name
 * @property AttachedObjectInterface metadata
 * @property int                     trial_period_days
 * @property string                  statement_descriptor
 */
interface PlanInterface
{
    /**
     * Retrieve a Plan
     * @link https://stripe.com/docs/api/php#retrieve_plan
     *
     * @param string            $id
     * @param array|string|null $options
     *
     * @return PlanInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Plans
     * @link https://stripe.com/docs/api/php#list_plans
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $options = null);

    /**
     * Create a plan
     * @link https://stripe.com/docs/api/php#create_plan
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return PlanInterface
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save a plan
     * @link https://stripe.com/docs/api/php#update_plan
     *
     * @return PlanInterface
     */
    public function save();

    /**
     * Delete a plan
     * @link https://stripe.com/docs/api/php#delete_plan
     *
     * @param array $params
     *
     * @return PlanInterface
     */
    public function delete($params = []);
}
