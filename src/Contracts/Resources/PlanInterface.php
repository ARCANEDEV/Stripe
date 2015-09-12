<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;

/**
 * Interface  PlanInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface PlanInterface
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
    public static function retrieve($id, $options = null);

    /**
     * List all Plans.
     *
     * @link   https://stripe.com/docs/api/php#list_plans
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null);

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
    public static function create($params = [], $options = null);

    /**
     * Update/Save a plan.
     *
     * @link   https://stripe.com/docs/api/php#update_plan
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

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
    public function delete($params = [], $options = null);
}
