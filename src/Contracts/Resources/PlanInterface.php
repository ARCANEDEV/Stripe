<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\Plan;

interface PlanInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Plan
     * @link https://stripe.com/docs/api/php#retrieve_plan
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return Plan
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Plans
     * @link https://stripe.com/docs/api/php#list_plans
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create a plan
     * @link https://stripe.com/docs/api/php#create_plan
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Plan|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save a plan
     * @link https://stripe.com/docs/api/php#update_plan
     *
     * @return Plan
     */
    public function save();

    /**
     * Delete a plan
     * @link https://stripe.com/docs/api/php#delete_plan
     *
     * @param  array|null $params
     *
     * @return Plan
     */
    public function delete($params = []);
}
