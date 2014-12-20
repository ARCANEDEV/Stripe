<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Api\Requestor;
use Arcanedev\Stripe\Api\Resource;

use Arcanedev\Stripe\Exceptions\InvalidRequestErrorException;

class ApplicationFeeRefund extends Resource
{
    /**
     * @throws InvalidRequestErrorException
     *
     * @return string The API URL for this Stripe refund.
     */
    public function instanceUrl()
    {
        $id     = $this['id'];
        $fee    = $this['fee'];
        if ( ! $id) {
            throw new InvalidRequestErrorException(
                "Could not determine which URL to request: class instance has invalid ID: $id", null
            );
        }

        $id         = Requestor::utf8($id);
        $fee        = Requestor::utf8($fee);

        $base       = self::classUrl('Stripe_ApplicationFee');
        $feeExtn    = urlencode($fee);
        $extn       = urlencode($id);

        return "$base/$feeExtn/refunds/$extn";
    }

    /**
     * @return ApplicationFeeRefund The saved refund.
     */
    public function save()
    {
        $class = get_class();

        return self::_scopedSave($class);
    }
}
