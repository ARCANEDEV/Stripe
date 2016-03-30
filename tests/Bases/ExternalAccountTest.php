<?php namespace Arcanedev\Stripe\Tests\Bases;

use Arcanedev\Stripe\Bases\ExternalAccount;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ExternalAccountTest
 *
 * @package  Arcanedev\Stripe\Tests\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ExternalAccountTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_verify()
    {
        $bankAccountToken = Token::create([
            'bank_account' => [
                'country'             => 'US',
                'routing_number'      => '110000000',
                'account_number'      => '000123456789',
                'account_holder_name' => 'Jane Austen',
                'account_holder_type' => 'company'
            ]
        ]);

        $customer        = Customer::create();
        /** @var ExternalAccount $externalAccount */
        $externalAccount = $customer->sources->create([
            'bank_account' => $bankAccountToken->id,
        ]);
        $verifiedAccount = $externalAccount->verify(['amounts' => [32, 45]], null);

        $this->assertEquals('verified', $verifiedAccount['status']);

        $base       = Customer::classUrl();
        $parentExtn = $externalAccount['customer'];
        $extn       = $externalAccount['id'];

        $this->assertEquals("$base/$parentExtn/sources/$extn", $externalAccount->instanceUrl());
    }
}
