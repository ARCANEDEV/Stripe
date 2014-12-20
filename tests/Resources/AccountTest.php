<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Account;

use Arcanedev\Stripe\Tests\StripeTest;

class AccountTest extends StripeTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
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
    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Account', $this->account);
        $this->assertEquals("cuD9Rwx8pgmRZRpVe02lsuR9cwp2Bzf7", $this->account->id);
        $this->assertEquals("test+bindings@stripe.com", $this->account->email);
        $this->assertFalse($this->account->charges_enabled);
        $this->assertFalse($this->account->details_submitted);
    }
}
