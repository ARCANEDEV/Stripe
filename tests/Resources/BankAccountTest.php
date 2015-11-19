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
                'account_holder_type' => 'individual',
                'country'             => 'US',
                'account_number'      => '000123456789',
                'routing_number'      => '110000000',
                'name'                => 'John Doe',
            ]
        ]);

        $this->assertEquals('new', $bankAccount->status);

        $bankAccount = $bankAccount->verify([
            'amounts' => [32, 45]
        ]);

        $this->assertEquals('verified', $bankAccount->status);
    }
}
