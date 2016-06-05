<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Invoice;
use Arcanedev\Stripe\Resources\InvoiceItem;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     InvoiceTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class InvoiceTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Invoice */
    private $invoice;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->invoice = new Invoice;
    }

    public function tearDown()
    {
        unset($this->invoice);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Invoice', $this->invoice);
    }

    /** @test */
    public function it_can_list_all()
    {
        // This is really just making sure that this operation does not trigger any warnings, as it's highly nested.
        $invoices = Invoice::all();

        $this->assertTrue($invoices->isList());
        $this->assertSame('/v1/invoices', $invoices->url);
    }

    /** @test */
    public function it_can_create()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer' => $customer->id,
            'amount'   => 0,
            'currency' => 'usd',
        ]);

        $this->invoice = Invoice::create([
            'customer' => $customer->id
        ]);

        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Invoice', $this->invoice);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer' => $customer->id,
            'amount'   => 0,
            'currency' => 'usd',
        ]);

        $invoice = Invoice::create([
            'customer' => $customer->id
        ]);

        $this->invoice = Invoice::retrieve($invoice->id);

        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Invoice', $this->invoice);
        $this->assertSame($invoice->id, $this->invoice->id);
    }

    /** @test */
    public function it_can_save()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer' => $customer->id,
            'amount'   => 0,
            'currency' => 'usd',
        ]);

        $invoice = Invoice::create([
            'customer' => $customer->id
        ]);

        $this->invoice = Invoice::retrieve($invoice->id);

        $description = 'Invoice Description';
        $this->invoice->description = $description;
        $this->invoice->save();

        $this->assertSame($description, $this->invoice->description);
    }

    /** @test */
    public function it_can_upcoming_invoice()
    {
        $customer = self::createTestCustomer();

        InvoiceItem::create([
            'customer' => $customer->id,
            'amount'   => 0,
            'currency' => 'usd',
        ]);

        $this->invoice = Invoice::upcoming([
            'customer' => $customer->id,
        ]);

        $this->assertSame($customer->id, $this->invoice->customer);
        $this->assertSame(false, $this->invoice->attempted);
    }

    /** @test */
    public function it_can_pay_invoice()
    {
        $customer = self::createTestCustomer();

        InvoiceItem::create([
            'customer' => $customer->id,
            'amount'   => 1000,
            'currency' => 'usd',
        ]);

        $this->invoice = Invoice::create([
            'customer' => $customer->id
        ]);

        $this->assertFalse($this->invoice->paid);

        $this->invoice->pay();

        $this->assertTrue($this->invoice->paid);
    }

    /** @test */
    public function it_can_access_items_with_parameters()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer' => $customer->id,
            'amount'   => 100,
            'currency' => 'usd',
        ]);

        $invoice = Invoice::upcoming([
            'customer' => $customer->id,
        ]);

        $lines = $invoice->lines->all([
            'limit' => 10,
        ]);

        $this->assertSame(1, count($lines->data));
        $this->assertSame(100, $lines->data[0]->amount);
    }
}
