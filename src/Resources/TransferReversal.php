<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\TransferReversalInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     TransferReversal
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#transfer_reversal_object
 *
 * @property  string                            id
 * @property  string                            object               // 'transfer_reversal'
 * @property  int                               amount
 * @property  string                            balance_transaction
 * @property  int                               created              // timestamp
 * @property  string                            currency
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            transfer
 */
class TransferReversal extends StripeResource implements TransferReversalInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get API URL for this Stripe transfer reversal.
     *
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl()
    {
        if (is_null($id = $this['id'])) {
            throw new InvalidRequestException(
                'Could not determine which URL to request: class instance has invalid ID [null]',
                null
            );
        }

        return implode('/', [
            Transfer::classUrl(),
            urlencode(str_utf8($this['transfer'])),
            'reversals',
            urlencode(str_utf8($id)),
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Saved the transfer reversal.
     * @link   https://stripe.com/docs/api/php#update_transfer_reversal
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }
}
