<?php namespace Arcanedev\Stripe\Tests\Resources;


use Arcanedev\Stripe\Resources\InvoiceItem;
use Arcanedev\Stripe\Tests\StripeTestCase;

class InvoiceItemTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var InvoiceItem */
    private $invoiceItem;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\InvoiceItem';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->invoiceItem = new InvoiceItem;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->invoiceItem);
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
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->invoiceItem);
    }

    /**
     * @test
     */
    public function testCanGetAll()
    {
        $invoices = InvoiceItem::all();

        $this->assertTrue($invoices->isList());
        $this->assertEquals('/v1/invoiceitems', $invoices->url);
    }

    /**
     * @test
     */
    public function testCanCreate()
    {
        $customer          = parent::createTestCustomer();
        $this->invoiceItem = self::createInvoiceItem($customer->id);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->invoiceItem);
        $this->assertEquals($customer->id, $this->invoiceItem->customer);
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $customer          = parent::createTestCustomer();
        $invoiceItem       = self::createInvoiceItem($customer->id);
        $this->invoiceItem = InvoiceItem::retrieve($invoiceItem->id);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->invoiceItem);
        $this->assertEquals($invoiceItem->id, $this->invoiceItem->id);
        $this->assertEquals($customer->id, $this->invoiceItem->customer);
    }

    /**
     * @test
     */
    public function testCanSave()
    {
        $customer          = parent::createTestCustomer();
        $this->invoiceItem = self::createInvoiceItem($customer->id);

        $description = 'Invoice Item Description';
        $this->invoiceItem->description = $description;
        $this->invoiceItem->save();

        $this->assertEquals($description, $this->invoiceItem->description);
    }

    /**
     * @test
     */
    public function testCanDelete()
    {
        $customer          = parent::createTestCustomer();
        $this->invoiceItem = self::createInvoiceItem($customer->id);
        $this->invoiceItem->delete();

        $this->assertTrue($this->invoiceItem->deleted);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create an Invoice Item
     *
     * @param string $customerId
     *
     * @return InvoiceItem
     */
    public static function createInvoiceItem($customerId)
    {
        return InvoiceItem::create([
            'customer'  => $customerId,
            'amount'    => 0,
            'currency'  => 'usd',
        ]);
    }
}
