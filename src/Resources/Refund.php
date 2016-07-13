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
 * @property  string                            object               // 'refund'
 * @property  int                               amount
 * @property  string                            balance_transaction
 * @property  string                            charge
 * @property  int                               created              // timestamp
 * @property  string                            currency
 * @property  string                            description
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            reason
 * @property  string                            receipt_number
 */
class Refund extends StripeResource implements RefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List all refunds.
     * @link   https://stripe.com/docs/api/php#list_refunds
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Refund by ID.
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
     * Create a Refund.
     * @link   https://stripe.com/docs/api/php#create_refund
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Update a Refund.
     * @link   https://stripe.com/docs/api/php#update_refund
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
     * Update/Save a Refund.
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
