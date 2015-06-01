<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\ApplicationFeeInterface;
use Arcanedev\Stripe\Resource;

/**
 * Class ApplicationFee
 * @package Arcanedev\Stripe\Resources
 * @link https://stripe.com/docs/api/php#application_fees
 *
 * @property string     id
 * @property string     object // "application_fee"
 * @property bool       livemode
 * @property string     account
 * @property int        amount
 * @property string     application
 * @property string     balance_transaction
 * @property string     charge
 * @property int        created // timestamp
 * @property string     currency
 * @property bool       refunded
 * @property Collection refunds
 * @property int        amount_refunded
 */
class ApplicationFee extends Resource implements ApplicationFeeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * This is a special case because the application fee endpoint has an
     *    underscore in it. The parent `className` function strips underscores.
     *
     * @param  string $class
     *
     * @return string The name of the class.
     */
    public static function className($class = '')
    {
        return parent::className();
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieving an Application Fee
     * @link https://stripe.com/docs/api/php#retrieve_application_fee
     *
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * List all Application Fees
     * @link https://stripe.com/docs/api/php#list_application_fees
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @return Collection|ApplicationFee[]
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Creating an Application Fee Refund
     * @link https://stripe.com/docs/api/php#create_fee_refund
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return self
     */
    public function refund($params = [], $options = null)
    {
        // TODO: Refactor to Requestor::make()
        list($response, $options) = $this->request('post', $this->instanceUrl() . '/refund', $params, $options);

        $this->refreshFrom($response, $options);

        return $this;
    }
}
