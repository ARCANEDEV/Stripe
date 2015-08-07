<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\TransferReversalInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\StripeResource;

/**
 * Class TransferReversal
 * @package Arcanedev\Stripe\Resources
 */
class TransferReversal extends StripeResource implements TransferReversalInterface
{
    /**
     * Get API URL for this Stripe transfer reversal
     *
     * @throws InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl()
    {
        $id = $this['id'];

        if ( ! $id) {
            throw new InvalidRequestException(
                'Could not determine which URL to request: class instance has invalid ID: ' . $id,
                null
            );
        }

        return implode('/', [
            Transfer::classUrl(),
            urlencode(str_utf8($this['transfer'])),
            'reversals',
            urlencode(str_utf8($id))
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Saved the transfer reversal
     *
     * @param  array|string|null $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }
}
