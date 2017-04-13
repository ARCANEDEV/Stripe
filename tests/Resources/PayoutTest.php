<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Payout;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class PayoutTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PayoutTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    private $managedAccount = null;

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_create()
    {
        $account = self::createAccountWithBalance();
        $payout  = self::createPayoutFromManagedAccount($account->id);

        $this->assertSame('pending', $payout->status);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $account = self::createAccountWithBalance();
        $payout  = self::createPayoutFromManagedAccount($account->id);

        $reloaded = Payout::retrieve($payout->id, ['stripe_account' => $account->id]);

        $this->assertSame($reloaded->id, $payout->id);
    }

    /** @test */
    public function it_can_update_metadata()
    {
        $account = self::createAccountWithBalance();
        $payout  =  self::createPayoutFromManagedAccount($account->id);

        $payout->metadata['test'] = 'foo bar';
        $payout->save();

        $updatedPayout = Payout::retrieve($payout->id, ['stripe_account' => $account->id]);

        $this->assertSame('foo bar', $updatedPayout->metadata['test']);
    }

    /** @test */
    public function it_can_update_all_metadata()
    {
        $account = self::createAccountWithBalance();
        $payout  =  self::createPayoutFromManagedAccount($account->id);

        $payout->metadata = ['test' => 'foo bar'];
        $payout->save();

        $updatedPayout = Payout::retrieve($payout->id, ['stripe_account' => $account->id]);

        $this->assertSame('foo bar', $updatedPayout->metadata['test']);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a managed account and put enough funds in the balance to be able to create a payout afterwards.
     * Also try to re-use the managed account across the tests to avoid hitting the rate limit for account creation.
     */
    private function createAccountWithBalance()
    {
        if ($this->managedAccount === null) {
            $account = self::createTestManagedAccount();

            Charge::create([
                'currency'    => 'usd',
                'amount'      => '10000',
                'source'      => [
                    'object' => 'card',
                    'number' => '4000000000000077',
                    'exp_month' => '09',
                    'exp_year' => date('Y') + 3,
                ],
                'destination' => [
                    'account' => $account->id
                ],
            ]);

            $this->managedAccount = $account;
        }

        return $this->managedAccount;
    }

    private function createPayoutFromManagedAccount($accountId)
    {
        return Payout::create(
            [
                'amount'   => 100,
                'currency' => 'usd',
            ],[
                'stripe_account' => $accountId
            ]
        );
    }
}
