<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ApplicationFeeInterface;
use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * Application Fee Object
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
 * @property ListObject refunds
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
     * @return ApplicationFee
     */
    public static function retrieve($id, $options = null)
    {
        return parent::scopedRetrieve($id, $options);
    }

    /**
     * List all Application Fees
     * @link https://stripe.com/docs/api/php#list_application_fees
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @return ListObject
     */
    public static function all($params = [], $options = null)
    {
        return parent::scopedAll($params, $options);
    }

    /**
     * Creating an Application Fee Refund
     * @link https://stripe.com/docs/api/php#create_fee_refund
     *
     * @param  array|null $params
     *
     * @return ApplicationFee
     */
    public function refund($params = [])
    {
        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($this->instanceUrl() . '/refund', $params);

        $this->refreshFrom($response, $apiKey);

        return $this;
    }
}
