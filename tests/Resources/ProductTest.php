<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Product;
use Arcanedev\Stripe\Resources\Sku;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ProductTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ProductTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string  */
    protected $productId = '';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->productId = 'gold-' . self::generateRandomString(20);
    }

    public function tearDown()
    {
        $this->productId = '';

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode     404
     * @expectedExceptionMessage  No such product: 0
     */
    public function it_must_throw_not_found_exception_on_invalid_product_id()
    {
        Product::retrieve('0');
    }

    /** @test */
    public function it_can_create_product()
    {
        $product = $this->createProduct();

        $this->assertEquals($product->url, 'www.stripe.com/gold');
    }

    /** @test */
    public function it_can_update_product()
    {
        $product       = $this->createProduct();
        $product->name = 'A new Product name';
        $product->save();

        $this->assertEquals($product->name, 'A new Product name');
        $this->assertEquals($product->url, 'www.stripe.com/gold');
    }

    /** @test */
    public function it_can_retrieve_product()
    {
        $product       = $this->createProduct();
        $stripeProduct = Product::retrieve($this->productId);

        $this->assertEquals($product->name,      $stripeProduct->name);
        $this->assertEquals($stripeProduct->url, 'www.stripe.com/gold');
    }

    /** @test */
    public function it_can_create_update_read_sku()
    {
        Product::create([
            'name' => 'Silver Product',
            'id'   => $this->productId,
            'url'  => 'www.stripe.com/silver-product'
        ]);

        $SkuID = 'silver-sku-' . self::generateRandomString(20);
        $sku   = Sku::create([
            'price'     => 500,
            'currency'  => 'usd',
            'id'        => $SkuID,
            'inventory' => [
                'type'     => 'finite',
                'quantity' => 40,
            ],
            'product'   => $this->productId,
        ]);

        $sku->price = 600;
        $sku->inventory->quantity = 50;
        $sku->save();

        $this->assertSame($sku->price, 600);
        $this->assertSame(50, $sku->inventory->quantity);

        $sku = Sku::retrieve($SkuID);
        $this->assertSame(600, $sku->price);
        $this->assertSame('finite', $sku->inventory->type);
        $this->assertSame(50, $sku->inventory->quantity);
    }

    /** @test */
    public function it_can_delete_sku_and_product()
    {
        $productId = 'silver-' . self::generateRandomString(20);
        $product   = Product::create([
            'name' => 'Silver Product',
            'id'   => $productId,
            'url'  => 'stripe.com/silver',
        ]);

        $SkuID = 'silver-sku-' . self::generateRandomString(20);
        $sku   = Sku::create([
            'price'     => 500,
            'currency'  => 'usd',
            'id'        => $SkuID,
            'inventory' => [
                'type'     => 'finite',
                'quantity' => 40,
            ],
            'product'   => $productId,
        ]);

        $deletedSku = $sku->delete();
        $this->assertTrue($deletedSku->deleted);

        $deletedProduct = $product->delete();
        $this->assertTrue($deletedProduct->deleted);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a product for tests.
     *
     * @return Product
     */
    protected function createProduct()
    {
        return Product::create([
            'name'  => 'Gold Product',
            'id'    => $this->productId,
            'url'   => 'www.stripe.com/gold'
        ]);
    }
}
