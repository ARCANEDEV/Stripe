<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Invoice;
use Arcanedev\Stripe\Resources\InvoiceItem;

use Arcanedev\Stripe\Tests\StripeTestCase;

class InvoiceTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Invoice */
    private $invoice;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\Invoice';

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
        parent::tearDown();

        unset($this->invoice);
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
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->invoice);
    }

    /**
     * @test
     *
     * This is really just making sure that this operation does not trigger any warnings, as it's highly nested.
     */
    public function testCanGetAll()
    {
        $invoices = Invoice::all();

        $this->assertTrue($invoices->isList());
        $this->assertEquals('/v1/invoices', $invoices->url);
    }

    /**
     * @test
     */
    public function testCanCreate()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer'  => $customer->id,
            'amount'    => 0,
            'currency'  => 'usd',
        ]);

        $this->invoice = Invoice::create([
            "customer" => $customer->id
        ]);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->invoice);
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer'  => $customer->id,
            'amount'    => 0,
            'currency'  => 'usd',
        ]);

        $invoice = Invoice::create([
            "customer" => $customer->id
        ]);

        $this->invoice = Invoice::retrieve($invoice->id);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->invoice);
        $this->assertEquals($invoice->id, $this->invoice->id);
    }

    /**
     * @test
     */
    public function testCanSave()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer'  => $customer->id,
            'amount'    => 0,
            'currency'  => 'usd',
        ]);

        $invoice = Invoice::create([
            "customer" => $customer->id
        ]);

        $this->invoice = Invoice::retrieve($invoice->id);

        $description = 'Invoice Description';
        $this->invoice->description = $description;
        $this->invoice->save();

        $this->assertEquals($description, $this->invoice->description);
    }

    /**
     * @test
     */
    public function testCanUpcoming()
    {
        $customer = self::createTestCustomer();

        InvoiceItem::create([
            'customer'  => $customer->id,
            'amount'    => 0,
            'currency'  => 'usd',
        ]);

        $this->invoice = Invoice::upcoming([
            'customer' => $customer->id,
        ]);

        $this->assertEquals($customer->id, $this->invoice->customer);
        $this->assertEquals(false, $this->invoice->attempted);
    }

    /**
     * @test
     */
    public function testCanPay()
    {
        $customer = self::createTestCustomer();

        InvoiceItem::create([
            'customer'  => $customer->id,
            'amount'    => 1000,
            'currency'  => 'usd',
        ]);

        $this->invoice = Invoice::create([
            "customer" => $customer->id
        ]);

        $this->assertFalse($this->invoice->paid);
        $this->invoice->pay();
        $this->assertTrue($this->invoice->paid);
    }

    /**
     * @test
     */
    public function testItemsAccessWithParameter()
    {
        $customer = parent::createTestCustomer();

        InvoiceItem::create([
            'customer'  => $customer->id,
            'amount'    => 100,
            'currency'  => 'usd',
        ]);

        $invoice = Invoice::upcoming([
            'customer' => $customer->id,
        ]);

        $lines = $invoice->lines->all([
            'limit' => 10,
        ]);

        $this->assertEquals(1, count($lines->data));
        $this->assertEquals(100, $lines->data[0]->amount);
    }
}
