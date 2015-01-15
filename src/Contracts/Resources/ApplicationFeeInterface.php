<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * Application Fee Object Interface
 * @link https://stripe.com/docs/api/php#application_fees
 *
 * @property string              id
 * @property string              object // "application_fee"
 * @property bool                livemode
 * @property string              account
 * @property int                 amount
 * @property string              application
 * @property string              balance_transaction
 * @property string              charge
 * @property int                 created // timestamp
 * @property string              currency
 * @property bool                refunded
 * @property ListObjectInterface refunds
 * @property int                 amount_refunded
 */
interface ApplicationFeeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieving an Application Fee
     * @link https://stripe.com/docs/api/php#retrieve_application_fee
     *
     * @param string            $id
     * @param array|string|null $options
     *
     * @return ApplicationFeeInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Application Fees
     * @link https://stripe.com/docs/api/php#list_application_fees
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $options = null);

    /**
     * Creating an Application Fee Refund
     * @link https://stripe.com/docs/api/php#create_fee_refund
     *
     * @param array $params
     *
     * @return ApplicationFeeInterface
     */
    public function refund($params = []);
}
