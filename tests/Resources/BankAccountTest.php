<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BankAccount;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     BankAccountTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BankAccountTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_verify()
    {
        $customer    = self::createTestCustomer();

        /** @var BankAccount $bankAccount */
        $bankAccount = $customer->sources->create([
            'source' => [
                'object'              => 'bank_account',
                'country'             => 'US',
                'account_number'      => '000123456789',
                'routing_number'      => '110000000',
                'account_holder_name' => 'John Doe',
                'account_holder_type' => 'individual',
            ]
        ]);

        $this->assertSame('new', $bankAccount->status);

        $bankAccount = $bankAccount->verify([
            'amounts' => [32, 45]
        ]);

        $this->assertSame('verified', $bankAccount->status);
    }
}
