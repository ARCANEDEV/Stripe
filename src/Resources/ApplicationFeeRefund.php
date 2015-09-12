<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\ApplicationFeeRefundInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     ApplicationFeeRefund
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#fee_refunds
 *
 * @property  string          id
 * @property  string          object                // "fee_refund"
 * @property  int             amount
 * @property  int             created
 * @property  string          currency
 * @property  string          balance_transaction
 * @property  string          fee
 * @property  AttachedObject  metadata
 *
 * @todo:     Complete the properties.
 */
class ApplicationFeeRefund extends StripeResource implements ApplicationFeeRefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const BASE_CLASS = 'Arcanedev\\Stripe\\Resources\\ApplicationFee';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
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

        $id      = $this['id'];
        $fee     = $this['fee'];

        $base    = self::classUrl(self::BASE_CLASS);
        $feeExtn = urlencode(str_utf8($fee));
        $extn    = urlencode(str_utf8($id));

        return "$base/$feeExtn/refunds/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
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
     * @throws InvalidRequestException
     */
    protected function checkId($id)
    {
        if ( ! $id) {
            throw new InvalidRequestException(
                'Could not determine which URL to request: class instance has invalid ID: ' . $id,
                400
            );
        }
    }
}
