<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\InvoiceItem;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     InvoiceItemTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class InvoiceItemTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var InvoiceItem */
    private $invoiceItem;

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
        unset($this->invoiceItem);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(InvoiceItem::class, $this->invoiceItem);
    }

    /** @test */
    public function it_can_list_all()
    {
        $invoices = InvoiceItem::all();

        $this->assertTrue($invoices->isList());
        $this->assertSame('/v1/invoiceitems', $invoices->url);
    }

    /** @test */
    public function it_can_create()
    {
        $customer          = parent::createTestCustomer();
        $this->invoiceItem = self::createInvoiceItem($customer->id);

        $this->assertInstanceOf(InvoiceItem::class, $this->invoiceItem);
        $this->assertSame($customer->id, $this->invoiceItem->customer);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $customer          = parent::createTestCustomer();
        $invoiceItem       = self::createInvoiceItem($customer->id);
        $this->invoiceItem = InvoiceItem::retrieve($invoiceItem->id);

        $this->assertInstanceOf(InvoiceItem::class, $this->invoiceItem);
        $this->assertSame($invoiceItem->id, $this->invoiceItem->id);
        $this->assertSame($customer->id, $this->invoiceItem->customer);
    }

    /** @test */
    public function it_can_update()
    {
        $customer          = parent::createTestCustomer();
        $this->invoiceItem = self::createInvoiceItem($customer->id);
        $this->invoiceItem = InvoiceItem::update($this->invoiceItem->id, [
            'description' => $description = 'Invoice Item Description',
        ]);

        $this->assertSame($customer->id, $this->invoiceItem->customer);
        $this->assertSame($description,  $this->invoiceItem->description);
    }

    /** @test */
    public function it_can_save()
    {
        $customer          = parent::createTestCustomer();
        $this->invoiceItem = self::createInvoiceItem($customer->id);

        $description = 'Invoice Item Description';
        $this->invoiceItem->description = $description;
        $this->invoiceItem->save();

        $this->assertSame($description, $this->invoiceItem->description);
    }

    /** @test */
    public function it_can_delete()
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
    private static function createInvoiceItem($customerId)
    {
        return InvoiceItem::create([
            'customer'  => $customerId,
            'amount'    => 0,
            'currency'  => 'usd',
        ]);
    }
}
