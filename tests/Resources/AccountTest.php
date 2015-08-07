<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class AccountTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
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
        $this->account = Account::retrieve();

        $this->assertInstanceOf(self::ACCOUNT_CLASS, $this->account);
        $this->assertEquals('cuD9Rwx8pgmRZRpVe02lsuR9cwp2Bzf7', $this->account->id);
        $this->assertEquals('test+bindings@stripe.com', $this->account->email);
        $this->assertFalse($this->account->charges_enabled);
        $this->assertFalse($this->account->details_submitted);
    }

    /** @test */
    public function it_can_retrieve_by_Id()
    {
        $id = 'cuD9Rwx8pgmRZRpVe02lsuR9cwp2Bzf7';
        $this->account = Account::retrieve($id);

        $this->assertEquals($id, $this->account->id);
        $this->assertEquals('test+bindings@stripe.com', $this->account->email);
    }

    /**
     * @todo: Add http client mock
     */
    public function it_can_delete()
    {
        $account = self::createTestAccount();

        $deleted = $account->delete();

        $this->assertEquals($deleted->id, $account->id);
        $this->assertTrue($deleted->deleted);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get deleted account response
     *
     * @param  string $id
     *
     * @return array
     */
    private function deletedAccountResponse($id)
    {
        return [
            'id'      => $id,
            'deleted' => true
        ];
    }
}
