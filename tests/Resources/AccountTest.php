<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Tests\StripeTestCase;

class AccountTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const ACCOUNT_CLASS = 'Arcanedev\\Stripe\\Resources\\Account';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Account */
    private $account;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->account = Account::retrieve();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->account);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_retrieve()
    {
        $this->assertInstanceOf(self::ACCOUNT_CLASS, $this->account);
        $this->assertEquals("cuD9Rwx8pgmRZRpVe02lsuR9cwp2Bzf7", $this->account->id);
        $this->assertEquals("test+bindings@stripe.com", $this->account->email);
        $this->assertFalse($this->account->charges_enabled);
        $this->assertFalse($this->account->details_submitted);
    }
}
