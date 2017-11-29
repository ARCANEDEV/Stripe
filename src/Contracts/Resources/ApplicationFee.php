<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  ApplicationFee
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ApplicationFee
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all Application Fees.
     * @link   https://stripe.com/docs/api/php#list_application_fees
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);

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
     * Creating an Application Fee Refund
     * @link   https://stripe.com/docs/api/php#create_fee_refund
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function refund($params = [], $options = null);
}
