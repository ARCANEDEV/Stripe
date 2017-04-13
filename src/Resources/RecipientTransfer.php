<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\StripeResource;

/**
 * Class RecipientTransfer
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string                            id
 * @property  string                            object
 * @property  int                               amount
 * @property  int                               amount_reversed
 * @property  string                            balance_transaction
 * @property  string                            bank_account
 * @property  string                            card
 * @property  int                               created
 * @property  string                            currency
 * @property  int                               date
 * @property  string                            description
 * @property  string                            destination
 * @property  string                            failure_code
 * @property  string                            failure_message
 * @property  bool                              livemode
 * @property  \Arcanedev\Stripe\AttachedObject  metadata
 * @property  string                            method
 * @property  string                            recipient
 * @property  mixed                             reversals
 * @property  bool                              reversed
 * @property  string                            source_type
 * @property  string                            statement_descriptor
 * @property  string                            status
 * @property  string                            type
 */
class RecipientTransfer extends StripeResource
{
    //
}