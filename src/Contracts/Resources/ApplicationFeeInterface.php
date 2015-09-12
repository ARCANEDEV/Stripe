<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;

/**
 * Interface  ApplicationFeeInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ApplicationFeeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieving an Application Fee.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_application_fee
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Application Fees.
     *
     * @link   https://stripe.com/docs/api/php#list_application_fees
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Creating an Application Fee Refund
     *
     * @link   https://stripe.com/docs/api/php#create_fee_refund
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function refund($params = [], $options = null);
}
