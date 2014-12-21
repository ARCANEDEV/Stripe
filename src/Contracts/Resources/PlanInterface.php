<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface PlanInterface
{
    /**
     * @param string      $id The ID of the plan to retrieve.
     * @param string|null $apiKey
     *
     * @return PlanInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Plans.
     */
    public static function all($params = null, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return PlanInterface The created plan.
     */
    public static function create($params = null, $apiKey = null);

    /**
     * @return PlanInterface The saved plan.
     */
    public function save();

    /**
     * @param array|null $params
     *
     * @return PlanInterface The deleted plan.
     */
    public function delete($params = null);
}
