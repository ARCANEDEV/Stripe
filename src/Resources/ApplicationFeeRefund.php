<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\ApplicationFeeRefundInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Resource;

/**
 * Application Fee Refund Object
 * @link https://stripe.com/docs/api/php#fee_refunds
 *
 * @property string         id
 * @property string         object  // "fee_refund"
 * @property int            amount
 * @property int            created
 * @property string         currency
 * @property string         balance_transaction
 * @property string         fee
 * @property AttachedObject metadata
 */
class ApplicationFeeRefund extends Resource implements ApplicationFeeRefundInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const BASE_CLASS = 'Arcanedev\\Stripe\\Resources\\ApplicationFee';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @throws InvalidRequestException
     *
     * @return string The API URL for this Stripe refund.
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
     * Update/Save an Application Fee Refund
     * @link https://stripe.com/docs/api/php#update_fee_refund
     *
     * @return ApplicationFeeRefund
     */
    public function save()
    {
        return self::scopedSave(get_class());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if id is not empty
     *
     * @param string $id
     *
     * @throws InvalidRequestException
     */
    protected function checkId($id)
    {
        if (! $id) {
            throw new InvalidRequestException(
                "Could not determine which URL to request: class instance has invalid ID: $id",
                400
            );
        }
    }
}
