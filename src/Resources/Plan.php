<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\PlanInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resource;

/**
 * Plan Object
 * @link https://stripe.com/docs/api/php#plan_object
 *
 * @property string         id
 * @property string         object // "plan"
 * @property bool           livemode
 * @property int            amount
 * @property int            created
 * @property int            currency
 * @property string         interval
 * @property int            interval_count
 * @property string         name
 * @property AttachedObject metadata
 * @property int            trial_period_days
 * @property string         statement_descriptor
 */
class Plan extends Resource implements PlanInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Plan
     * @link https://stripe.com/docs/api/php#retrieve_plan
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return Plan
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve(get_class(), $id, $apiKey);
    }

    /**
     * List all Plans
     * @link https://stripe.com/docs/api/php#list_plans
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObject
     */
    public static function all($params = [], $apiKey = null)
    {
        return self::scopedAll(get_class(), $params, $apiKey);
    }

    /**
     * Create a plan
     * @link https://stripe.com/docs/api/php#create_plan
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return Plan
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate(get_class(), $params, $apiKey);
    }

    /**
     * Update/Save a plan
     * @link https://stripe.com/docs/api/php#update_plan
     *
     * @return Plan
     */
    public function save()
    {
        return self::scopedSave(get_class());
    }

    /**
     * Delete a plan
     * @link https://stripe.com/docs/api/php#delete_plan
     *
     * @param array $params
     *
     * @return Plan
     */
    public function delete($params = [])
    {
        return self::scopedDelete(get_class(), $params);
    }
}
