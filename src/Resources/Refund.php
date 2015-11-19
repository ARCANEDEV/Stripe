<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\RefundInterface;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     Refund
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#refund_object
 *
 * @property  string                            id
 * @property  string                            object                // "refund"
 * @property  int                               amount
 * @property  int                               created
 * @property  string                            currency
 * @property  string                            balance_transaction
 * @property  string                            charge
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            reason
 * @property  string                            receipt_number
 * @property  string                            description
 *
 * @todo:     Complete the properties.
 */
class Refund extends StripeResource implements RefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a Refund by ID.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_refund
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
     * List all refunds.
     *
     * @link   https://stripe.com/docs/api/php#list_refunds
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Create a Refund.
     *
     * @link   https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update/Save a Refund.
     *
     * @link   https://stripe.com/docs/api/php#update_refund
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }
}
