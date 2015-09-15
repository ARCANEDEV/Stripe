<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Product;
use Arcanedev\Stripe\Resources\Sku;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     SkuTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SkuTest extends StripeTestCase
{
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
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_create_sku()
    {
        $sku = $this->createSku();

        $this->assertEquals($sku->price, 500);
        $this->assertEquals('finite', $sku->inventory->type);
        $this->assertEquals(40, $sku->inventory->quantity);
    }

    /** @test */
    public function it_can_update_sku()
    {
        $sku = $this->createSku();
        $sku->price = 600;
        $sku->save();

        $this->assertEquals($sku->price, 600);
        $this->assertEquals('finite', $sku->inventory->type);
        $this->assertEquals(40, $sku->inventory->quantity);
    }

    /** @test */
    public function it_retrieve_sku()
    {
        $sku       = $this->createSku();
        $stripeSku = SKU::retrieve($sku->id);

        $this->assertEquals($stripeSku->price, 500);
        $this->assertEquals('finite', $stripeSku->inventory->type);
        $this->assertEquals(40, $stripeSku->inventory->quantity);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create SKU.
     *
     * @return Sku
     */
    private function createSku()
    {
        $product = $this->createProduct();

        return SKU::create([
            'price'     => 500,
            'currency'  => 'usd',
            'id'        => 'silver-sku-' . self::generateRandomString(20),
            'inventory' => [
                'type'     => 'finite',
                'quantity' => 40
            ],
            'product'   => $product->id
        ]);
    }

    /**
     * Create a product for tests.
     *
     * @return Product
     */
    protected function createProduct()
    {
        return Product::create([
            'name'  => 'Silver Product',
            'id'    => 'silver-' . self::generateRandomString(20),
            'url'   => 'www.stripe.com/silver'
        ]);
    }
}
