<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTestCase;

class CustomerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const CUSTOMER_CLASS    = 'Arcanedev\\Stripe\\Resources\\Customer';
    const LIST_OBJECT_CLASS = 'Arcanedev\\Stripe\\ListObject';

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
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::CUSTOMER_CLASS, $this->customer);
    }

    /** @test */
    public function it_can_list_all()
    {
        $customers = Customer::all();

        $this->assertInstanceOf(self::LIST_OBJECT_CLASS, $customers);
    }

    /** @test */
    public function it_can_create_and_save()
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

    /** @test */
    public function it_can_delete()
    {
        $customer = self::createTestCustomer();
        $customer->delete();

        $this->assertTrue($customer->deleted);
        $this->assertNull($customer['active_card']);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function it_must_throw_invalid_request_error_on_bogus_attribute()
    {
        $customer        = self::createTestCustomer();
        $customer->bogus = 'bogus';
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function it_must_throw_invalid_argument_exception_on_update_description()
    {
        $customer = self::createTestCustomer();
        $customer->description = '';
    }

    /** @test */
    public function it_can_update_description()
    {
        $customer = self::createTestCustomer(['description' => 'foo bar']);
        $customer->description = null;

        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals(null, $updatedCustomer->description);
    }

    // TODO: Add Test MustBeNull Exception on description update

    /** @test */
    public function it_can_update_metadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata['test'] = 'foo bar';
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals('foo bar', $updatedCustomer->metadata['test']);
    }

    /** @test */
    public function it_can_delete_metadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata = null;
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertEquals(0, count($updatedCustomer->metadata->keys()));
    }

    /** @test */
    public function it_can_update_some_metadata()
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

    /** @test */
    public function it_can_update_all_metadatas()
    {
        $customer = self::createTestCustomer();
        $customer->metadata['shoe size']  = '7';
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
    public function it_must_throw_invalid_request_error_on_update_with_invalid_metadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata = 'something';
        $customer->save();
    }

    /** @test */
    public function it_can_get_all_invoices()
    {
        $customer   = self::createTestCustomer();
        $invoices   = $customer->invoices();

        $this->assertInstanceOf(
            self::LIST_OBJECT_CLASS,
            $invoices
        );
        $this->assertEquals('/v1/invoices', $invoices->url);
    }

    /** @test */
    public function it_can_add_invoice_item()
    {
        $customer    = self::createTestCustomer();
        $invoiceItem = $customer->addInvoiceItem([
            'amount'    => 200,
            'currency'  => 'usd'
        ]);

        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\InvoiceItem',
            $invoiceItem
        );
        $this->assertEquals(200, $invoiceItem->amount);
        $this->assertEquals('usd', $invoiceItem->currency);
    }

    /** @test */
    public function it_can_list_all_invoice_items()
    {
        $customer    = self::createTestCustomer();
        $customer->addInvoiceItem([
            'amount'    => 200,
            'currency'  => 'usd'
        ]);

        $invoiceItems = $customer->invoiceItems();
        $this->assertInstanceOf(self::LIST_OBJECT_CLASS, $invoiceItems);
    }

    /** @test */
    public function it_can_list_all_charges()
    {
        $customer   = self::createTestCustomer();

        $charges    = $customer->charges();
        $this->assertInstanceOf(self::LIST_OBJECT_CLASS, $charges);
    }

    /** @test */
    public function it_can_add_subscription()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer([
            'plan'  => $plan->id,
        ]);

        $customer = Customer::retrieve($customer->id);

        $this->assertInstanceOf(
            self::LIST_OBJECT_CLASS,
            $customer->subscriptions
        );
        $this->assertEquals(1, $customer->subscriptions->count());
    }

    /** @test */
    public function it_can_update_subscription()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer([
            'plan'  => $plan->id,
        ]);

        $subscription = $customer->updateSubscription([
            'plan'      => $plan->id,
            'quantity'  => 4,
        ]);

        $this->assertEquals(4, $subscription->quantity);
    }

    /** @test */
    public function it_can_cancel_subscription()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer([
            'plan'  => $plan->id,
        ]);

        $customer->cancelSubscription([
            'at_period_end' => true
        ]);

        $this->assertEquals($customer->subscription->status, 'active');
        $this->assertTrue($customer->subscription->cancel_at_period_end);

        $customer->cancelSubscription();
        $this->assertEquals($customer->subscription->status, 'canceled');
    }

    /** @test */
    public function it_can_create_discount()
    {
        $couponId = "25OFF";
        parent::retrieveOrCreateCoupon($couponId);

        $customer = Customer::create([
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 5,
                'exp_year'  => 2015
            ],
            'coupon' => $couponId,
        ]);

        $discount = $customer->discount;

        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\Discount',
            $discount
        );
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\Coupon',
            $discount->coupon
        );

        $this->assertEquals($couponId, $discount->coupon->id);
    }

    /** @test */
    public function it_can_delete_discount()
    {
        $couponId = "25OFF";
        parent::retrieveOrCreateCoupon($couponId);

        $customer = Customer::create([
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 5,
                'exp_year'  => 2015
            ],
            'coupon' => $couponId,
        ]);

        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\Discount',
            $customer->discount
        );

        $customer->deleteDiscount();
        $this->assertNull($customer->discount);
    }

    /** @test */
    public function it_can_add_card()
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

    /** @test */
    public function it_can_update_card()
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

    /** @test */
    public function it_can_delete_card()
    {
        $token          = $this->createToken();
        $customer       = $this->createTestCustomer();
        $createdCard    = $customer->cards->create([
            "card" => $token->id
        ]);
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedCards    = $updatedCustomer->cards->all();
        $this->assertEquals(count($updatedCards["data"]), 2);

        $deleteStatus    = $updatedCustomer->cards->retrieve($createdCard->id)->delete();

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
