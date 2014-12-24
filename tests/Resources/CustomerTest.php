<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\Token;

use Arcanedev\Stripe\Tests\StripeTestCase;

class CustomerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Customer */
    private $customer;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->customer = new Customer;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->customer);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Customer', $this->customer);
    }
    /**
     * @test
     */
    public function testCanDelete()
    {
        $customer = self::createTestCustomer();
        $customer->delete();

        $this->assertTrue($customer->deleted);
        $this->assertNull($customer['active_card']);
    }

    /**
     * @test
     */
    public function testCanCreateAndSave()
    {
        $customer = self::createTestCustomer();

        $customer->email = 'gdb@stripe.com';
        $customer->save();
        $this->assertEquals('gdb@stripe.com', $customer->email);

        $stripeCustomer = Customer::retrieve($customer->id);
        $this->assertEquals($customer->email, $stripeCustomer->email);

        $customer = Customer::create(null);
        $customer->email = 'gdb@stripe.com';
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals('gdb@stripe.com', $updatedCustomer->email);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function testMustThrowInvalidRequestErrorOnBogusAttribute()
    {
        $customer           = self::createTestCustomer();
        $customer->bogus    = 'bogus';
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function testMustThrowInvalidArgumentExceptionOnUpdateDescription()
    {
        $customer = self::createTestCustomer();
        $customer->description = '';
    }

    /**
     * @test
     */
    public function testCanUpdateDescription()
    {
        $customer = self::createTestCustomer(['description' => 'foo bar']);
        $customer->description = null;

        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals(null, $updatedCustomer->description);
    }

    // TODO: Add Test MustBeNull Exception on description update

    /**
     * @test
     */
    public function testCanUpdateMetadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata['test'] = 'foo bar';
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals('foo bar', $updatedCustomer->metadata['test']);
    }

    /**
     * @test
     */
    public function testCanDeleteMetadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata = null;
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals(0, count($updatedCustomer->metadata->keys()));
    }

    /**
     * @test
     */
    public function testCanUpdateSomeMetadata()
    {
        $customer = self::createTestCustomer();
        $customer->metadata['shoe size'] = '7';
        $customer->metadata['shirt size'] = 'XS';
        $customer->save();

        $customer->metadata['shoe size'] = '9';
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals('9', $updatedCustomer->metadata['shoe size']);
        $this->assertEquals('XS', $updatedCustomer->metadata['shirt size']);
    }

    /**
     * @test
     */
    public function testCanUpdateAllMetadata()
    {
        $customer = self::createTestCustomer();
        $customer->metadata['shoe size'] = '7';
        $customer->metadata['shirt size'] = 'XS';
        $customer->save();

        $customer->metadata = ['shirt size' => 'XL'];
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals('XL', $updatedCustomer->metadata['shirt size']);
        $this->assertFalse(isset($updatedCustomer->metadata['shoe size']));
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function testMustThrowInvalidRequestErrorOnUpdateWithInvalidMetadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata = 'something';
        $customer->save();
    }

    /**
     * @test
     */
    public function testCanCancelSubscription()
    {
        $planID = 'gold-' . self::randomString();
        self::retrieveOrCreatePlan($planID);

        $customer = self::createTestCustomer([
            'plan'  => $planID,
        ]);

        $customer->cancelSubscription([
            'at_period_end' => true
        ]);

        $this->assertEquals($customer->subscription->status, 'active');
        $this->assertTrue($customer->subscription->cancel_at_period_end);

        $customer->cancelSubscription();
        $this->assertEquals($customer->subscription->status, 'canceled');
    }

    /**
     * @test
     */
    public function testCanAddCard()
    {
        $token      = $this->createToken();
        $customer   = $this->createTestCustomer();

        $customer->cards->create([
            "card" => $token->id
        ]);

        $customer->save();

        $updatedCustomer    = Customer::retrieve($customer->id);
        $updatedCards       = $updatedCustomer->cards->all();
        $this->assertEquals(count($updatedCards["data"]), 2);
    }

    /**
     * @test
     */
    public function testCanUpdateCard()
    {
        $customer = $this->createTestCustomer();
        $customer->save();

        $cards = $customer->cards->all();
        $this->assertEquals(count($cards["data"]), 1);

        /** @var \Arcanedev\Stripe\Resources\Card $card */
        $card       = $cards['data'][0];
        $card->name = "Jane Austen";
        $card->save();

        $updatedCustomer    = Customer::retrieve($customer->id);
        $updatedCards       = $updatedCustomer->cards->all();
        $this->assertEquals("Jane Austen", $updatedCards["data"][0]->name);
    }

    /**
     * @test
     */
    public function testCanDeleteCard()
    {
        $token          = $this->createToken();
        $customer       = $this->createTestCustomer();
        $createdCard    = $customer->cards->create([
            "card" => $token->id
        ]);
        $customer->save();

        $updatedCustomer    = Customer::retrieve($customer->id);
        $updatedCards       = $updatedCustomer->cards->all();
        $this->assertEquals(count($updatedCards["data"]), 2);

        $deleteStatus = $updatedCustomer->cards->retrieve($createdCard->id)->delete();

        $this->assertEquals(true, $deleteStatus->deleted);
        $updatedCustomer->save();

        $postDeleteCustomer = Customer::retrieve($customer->id);
        $postDeleteCards    = $postDeleteCustomer->cards->all();
        $this->assertEquals(1, count($postDeleteCards["data"]));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Token fo tests
     *
     * @return \Arcanedev\Stripe\Resources\Token
     */
    private function createToken()
    {
        return Token::create([
            "card"  => [
                "number"    => "4242424242424242",
                "exp_month" => 5,
                "exp_year"  => date('Y') + 3,
                "cvc"       => "314"
            ]
        ]);
    }
}
