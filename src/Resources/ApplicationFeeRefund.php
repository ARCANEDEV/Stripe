<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ApplicationFeeRefund as ApplicationFeeRefundContract;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     ApplicationFeeRefund
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#fee_refunds
 *
 * @property  string                            id
 * @property  string                            object                // "fee_refund"
 * @property  int                               amount
 * @property  string                            balance_transaction
 * @property  int                               created
 * @property  string                            currency
 * @property  string                            fee
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 */
class ApplicationFeeRefund extends StripeResource implements ApplicationFeeRefundContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get instance URL - The API URL for this Stripe refund.
     *
     * @return string
     */
    public function instanceUrl()
    {
        $this->checkId($this['id']);

        return implode('/', [
            self::classUrl(ApplicationFee::class),
            urlencode(str_utf8($this['fee'])),
            'refunds',
            urlencode(str_utf8($this['id']))
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update/Save an Application Fee Refund.
     *
     * @link   https://stripe.com/docs/api/php#update_fee_refund
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if id is not empty.
     *
     * @param  string  $id
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    protected function checkId($id)
    {
        if ( ! $id)
            throw new InvalidRequestException(
                "Could not determine which URL to request: class instance has invalid ID: {$id}", 400
            );
    }
}
