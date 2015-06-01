<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Contracts\Resources\RefundInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Resource;

/**
 * Class Refund
 * @package Arcanedev\Stripe\Resources
 * @link https://stripe.com/docs/api/php#refund_object
 *
 * @property string         id
 * @property string         object // "refund"
 * @property int            amount
 * @property int            created
 * @property string         currency
 * @property string         balance_transaction
 * @property string         charge
 * @property AttachedObject metadata
 * @property string         reason
 * @property string         receipt_number
 * @property string         description
 */
class Refund extends Resource implements RefundInterface
{
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
        // TODO: Refactor this method
        $id       = $this['id'];

        if ( ! $id) {
            throw new InvalidRequestException(
                'Could not determine which URL to request: class instance has invalid ID: '. $id,
                null
            );
        }

        $base     = self::classUrl('Arcanedev\\Stripe\\Resources\\Charge');
        $chargeId = urlencode(str_utf8($this['charge']));
        $refundId = urlencode(str_utf8($id));

        return "$base/$chargeId/refunds/$refundId";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update/Save a Refund
     * @link https://stripe.com/docs/api/php#update_refund
     *
     * @param  array|string|null $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return self::scopedSave($options);
    }
}
