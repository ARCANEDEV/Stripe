<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ApplicationFeeInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     ApplicationFee
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#application_fees
 *
 * @property  string                        id
 * @property  string                        object                   // "application_fee"
 * @property  string                        account
 * @property  int                           amount
 * @property  int                           amount_refunded
 * @property  string                        application
 * @property  string                        balance_transaction
 * @property  string                        charge
 * @property  int                           created                  // timestamp
 * @property  string                        currency
 * @property  bool                          livemode
 * @property  string                        originating_transaction
 * @property  bool                          refunded
 * @property  \Arcanedev\Stripe\Collection  refunds
 */
class ApplicationFee extends StripeResource implements ApplicationFeeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * This is a special case because the application fee endpoint has an
     *    underscore in it. The parent `className` function strips underscores.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function className($class = '')
    {
        return parent::className();
    }

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
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

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
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Update an Application Fee.
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
     * Creating an Application Fee Refund.
     *
     * @link   https://stripe.com/docs/api/php#create_fee_refund
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function refund($params = [], $options = null)
    {
        $this->refunds->create($params, $options);
        $this->refresh();

        return $this;
    }
}
