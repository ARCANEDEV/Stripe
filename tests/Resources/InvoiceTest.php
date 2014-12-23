<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Invoice;
use Arcanedev\Stripe\Resources\InvoiceItem;
use Arcanedev\Stripe\Tests\StripeTest;

class InvoiceTest extends StripeTest
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
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Invoice', $this->invoice);
    }

    /**
     * @test
     *
     * This is really just making sure that this operation does not trigger any warnings, as it's highly nested.
     */
    public function testCanGetAll()
    {
        $invoices = Invoice::all();

        $this->assertTrue(count($invoices) > 0);
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

        $invoice = Invoice::upcoming([
            'customer' => $customer->id,
        ]);

        $this->assertEquals($customer->id, $invoice->customer);
        $this->assertEquals(false, $invoice->attempted);
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
