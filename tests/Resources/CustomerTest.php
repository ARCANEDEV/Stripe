<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\Discount;
use Arcanedev\Stripe\Resources\InvoiceItem;
use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     CustomerTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
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
        unset($this->customer);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Customer::class, $this->customer);
    }

    /** @test */
    public function it_can_list_all()
    {
        $customers = Customer::all();

        $this->assertInstanceOf(Collection::class, $customers);
    }

    /** @test */
    public function it_can_create_and_save()
    {
        $customer        = self::createTestCustomer();
        $customer->email = 'gdb@stripe.com';
        $customer->save();
        $stripeCustomer  = Customer::retrieve($customer->id);

        $this->assertSame('gdb@stripe.com', $customer->email);
        $this->assertSame($customer->email, $stripeCustomer->email);

        $customer        = Customer::create(null);
        $customer->email = 'gdb@stripe.com';
        $customer->save();
        $updatedCustomer = Customer::retrieve($customer->id);

        $this->assertSame('gdb@stripe.com', $customer->email);
        $this->assertSame($customer->email, $updatedCustomer->email);
    }

    /** @test */
    public function it_can_update()
    {
        $customer = self::createTestCustomer();
        $customer = Customer::update($customer->id, [
            'email' => 'gdb@stripe.com',
        ]);
        $stripeCustomer = Customer::retrieve($customer->id);

        $this->assertSame('gdb@stripe.com', $customer->email);
        $this->assertSame($customer->email, $stripeCustomer->email);

        $customer  = Customer::create(null);
        $customer  = Customer::update($customer->id, [
            'email' => 'gdb@stripe.com',
        ]);
        $updatedCustomer = Customer::retrieve($customer->id);

        $this->assertSame('gdb@stripe.com', $customer->email);
        $this->assertSame($customer->email, $updatedCustomer->email);
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
        $this->assertSame(null, $updatedCustomer->description);
    }

    // TODO: Add Test MustBeNull Exception on description update

    /** @test */
    public function it_can_update_metadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata['test'] = 'foo bar';
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame('foo bar', $updatedCustomer->metadata['test']);
    }

    /** @test */
    public function it_can_delete_metadata()
    {
        $customer = self::createTestCustomer();

        $customer->metadata = null;
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame(0, count($updatedCustomer->metadata->keys()));
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
        $this->assertSame('9', $updatedCustomer->metadata['shoe size']);
        $this->assertSame('XS', $updatedCustomer->metadata['shirt size']);
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
        $this->assertSame('XL', $updatedCustomer->metadata['shirt size']);
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

        $this->assertInstanceOf(Collection::class, $invoices);
        $this->assertSame('/v1/invoices', $invoices->url);
    }

    /** @test */
    public function it_can_add_invoice_item()
    {
        $customer    = self::createTestCustomer();
        $invoiceItem = $customer->addInvoiceItem([
            'amount'   => 200,
            'currency' => 'usd',
        ]);

        $this->assertInstanceOf(InvoiceItem::class, $invoiceItem);
        $this->assertSame(200, $invoiceItem->amount);
        $this->assertSame('usd', $invoiceItem->currency);
    }

    /** @test */
    public function it_can_list_all_invoice_items()
    {
        $customer    = self::createTestCustomer();
        $customer->addInvoiceItem([
            'amount'   => 200,
            'currency' => 'usd',
        ]);

        $invoiceItems = $customer->invoiceItems();
        $this->assertInstanceOf(Collection::class, $invoiceItems);
    }

    /** @test */
    public function it_can_list_all_charges()
    {
        $customer   = self::createTestCustomer();

        $charges    = $customer->charges();
        $this->assertInstanceOf(Collection::class, $charges);
    }

    /** @test */
    public function it_can_add_subscription()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer([
            'plan'  => $plan->id,
        ]);

        $customer = Customer::retrieve($customer->id);

        $this->assertInstanceOf(Collection::class, $customer->subscriptions);
        $this->assertSame(1, $customer->subscriptions->count());
    }

    /** @test */
    public function it_can_update_subscription()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer([
            'plan'  => $plan->id,
        ]);

        $subscription = $customer->updateSubscription([
            'plan'     => $plan->id,
            'quantity' => 4,
        ]);

        $this->assertSame(4, $subscription->quantity);
    }

    /** @test */
    public function it_can_cancel_subscription()
    {
        $plan     = self::retrieveOrCreatePlan();
        $customer = self::createTestCustomer([
            'plan' => $plan->id,
        ]);

        $customer->cancelSubscription([
            'at_period_end' => true,
        ]);

        $this->assertSame($customer->subscription->status, 'active');
        $this->assertTrue($customer->subscription->cancel_at_period_end);

        $customer->cancelSubscription();

        $this->assertSame($customer->subscription->status, 'canceled');
    }

    /** @test */
    public function it_can_create_discount()
    {
        $couponId = '25OFF';
        parent::retrieveOrCreateCoupon($couponId);

        $customer = Customer::create([
            'card'      => self::getValidCardData(),
            'coupon'    => $couponId,
        ]);

        $discount = $customer->discount;

        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertInstanceOf(Coupon::class, $discount->coupon);

        $this->assertSame($couponId, $discount->coupon->id);
    }

    /** @test */
    public function it_can_delete_discount()
    {
        $couponId = '25OFF';
        parent::retrieveOrCreateCoupon($couponId);

        $customer = Customer::create([
            'card'   => self::getValidCardData(),
            'coupon' => $couponId,
        ]);

        $this->assertInstanceOf(Discount::class, $customer->discount);

        $customer->deleteDiscount();

        $this->assertNull($customer->discount);
    }

    /** @test */
    public function it_can_add_card()
    {
        $token    = $this->createToken();
        $customer = $this->createTestCustomer();

        $customer->sources->create(['card' => $token->id]);

        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedCards    = $updatedCustomer->sources->all();
        $this->assertSame(2, count($updatedCards['data']));
    }

    /** @test */
    public function it_can_update_card()
    {
        $customer = $this->createTestCustomer();
        $customer->save();

        $cards = $customer->sources->all();
        $this->assertSame(count($cards['data']), 1);

        /** @var \Arcanedev\Stripe\Resources\Card $card */
        $card       = $cards['data'][0];
        $card->name = 'Jane Austen';
        $card->save();

        $updatedCustomer    = Customer::retrieve($customer->id);
        $updatedCards       = $updatedCustomer->sources->all();
        $this->assertSame('Jane Austen', $updatedCards['data'][0]->name);
    }

    /** @test */
    public function it_can_delete_card()
    {
        $token       = $this->createToken();
        $customer    = $this->createTestCustomer();
        $createdCard = $customer->sources->create([
            'card' => $token->id
        ]);
        $customer->save();

        $updatedCustomer = Customer::retrieve($customer->id);
        $updatedCards    = $updatedCustomer->sources->all();
        $this->assertSame(2, count($updatedCards['data']));

        /** @var \Arcanedev\Stripe\Resources\Card $card */
        $card = $updatedCustomer->sources->retrieve($createdCard->id);
        $card = $card->delete();

        $this->assertSame(true, $card->deleted);
        $updatedCustomer->save();

        $postDeleteCustomer = Customer::retrieve($customer->id);
        $postDeleteCards    = $postDeleteCustomer->sources->all();
        $this->assertSame(1, count($postDeleteCards['data']));
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
            'card'  => self::getValidCardData('314'),
        ]);
    }
}
