<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\RefundInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * @property string     id
 * @property mixed|null amount
 * @property mixed|null charge
 * @property mixed|null metadata
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
        $id     = $this['id'];
        $charge = $this['charge'];

        if ( ! $id) {
            throw new InvalidRequestException(
                "Could not determine which URL to request: class instance has invalid ID: $id", null
            );
        }

        $id     = Requestor::utf8($id);
        $charge = Requestor::utf8($charge);

        $base       = self::classUrl('Arcanedev\\Stripe\\Resources\\Charge');
        $chargeExtn = urlencode($charge);
        $extn       = urlencode($id);

        return "$base/$chargeExtn/refunds/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return Refund The saved refund.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }
}
