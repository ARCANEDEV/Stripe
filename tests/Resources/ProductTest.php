<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Order;
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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string  */
    protected $productId = '';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->productId = 'gold-'.self::generateRandomString(20);
    }

    public function tearDown()
    {
        $this->productId = '';

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_retrieve()
    {
        $product       = $this->createProduct();
        $stripeProduct = Product::retrieve($this->productId);

        $this->assertSame($product->name,        $stripeProduct->name);
        $this->assertSame('www.stripe.com/gold', $stripeProduct->url);
    }

    /** @test */
    public function it_can_get_all()
    {
        $products = Product::all(['include' => ['total_count']]);

        $this->assertInstanceOf(Collection::class, $products);
        $this->assertTrue($products->isList());

        if ($products->count() > 0) {
            $this->assertSame($products->count(), $products->total_count);
        }
    }

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
    public function it_can_create()
    {
        $product = $this->createProduct();

        $this->assertSame('www.stripe.com/gold', $product->url);
    }

    /** @test */
    public function it_can_update()
    {
        $product = $this->createProduct();
        $product = Product::update($product->id, [
            'name' => 'A new Product name',
        ]);

        $this->assertSame('A new Product name',  $product->name);
        $this->assertSame('www.stripe.com/gold', $product->url);
    }

    /** @test */
    public function it_can_save()
    {
        $product       = $this->createProduct();
        $product->name = 'A new Product name';
        $product->save();

        $this->assertSame('A new Product name',  $product->name);
        $this->assertSame('www.stripe.com/gold', $product->url);
    }

    /** @test */
    public function it_can_create_edit_read_sku()
    {
        Product::create([
            'name' => 'Silver Product',
            'id'   => $this->productId,
            'url'  => 'www.stripe.com/silver-product',
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

        $this->assertSame(600, $sku->price);
        $this->assertSame(50,  $sku->inventory->quantity);

        $sku = Sku::retrieve($SkuID);

        $this->assertSame(600,      $sku->price);
        $this->assertSame('finite', $sku->inventory->type);
        $this->assertSame(50,       $sku->inventory->quantity);
    }

    /** @test */
    public function it_can_delete_sku_and_product()
    {
        $productId = 'silver-'.self::generateRandomString(20);
        $product   = Product::create([
            'name' => 'Silver Product',
            'id'   => $productId,
            'url'  => 'stripe.com/silver',
        ]);

        $skuID = 'silver-sku-'.self::generateRandomString(20);
        $sku   = Sku::create([
            'price'     => 500,
            'currency'  => 'usd',
            'id'        => $skuID,
            'inventory' => [
                'type'     => 'finite',
                'quantity' => 40,
            ],
            'product'   => $productId,
        ]);

        $deletedSku     = $sku->delete();

        $this->assertTrue($deletedSku->deleted);

        $deletedProduct = $product->delete();

        $this->assertTrue($deletedProduct->deleted);
    }

    /** @test */
    public function it_can_create_update_retrieve_pay_and_return()
    {
        $productId = 'silver-'.self::generateRandomString(20);
        Product::create([
            'name'      => 'Silver Product',
            'id'        => $productId,
            'url'       => 'www.stripe.com/silver',
            'shippable' => false,
        ]);

        $skuID = 'silver-sku-'.self::generateRandomString(20);
        SKU::create([
            'price'     => 500,
            'currency'  => 'usd',
            'id'        => $skuID,
            'inventory' => [
                'type'     => 'finite',
                'quantity' => 40
            ],
            'product'   => $productId
        ]);

        $order = Order::create([
            'items'    => [
                [
                    'type'   => 'sku',
                    'parent' => $skuID,
                ],
            ],
            'currency' => 'usd',
            'email'    => 'foo@bar.com',
        ]);

        $order->metadata->foo = 'bar';
        $order->save();

        $stripeOrder = Order::retrieve($order->id);

        $this->assertSame('bar', $stripeOrder->metadata->foo);

        $stripeOrder->pay(['source' => 'tok_visa']);

        $this->assertSame('paid', $stripeOrder->status);

        $orderReturn = $stripeOrder->returnOrder();

        $this->assertSame($orderReturn->order, $stripeOrder->id);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a product for tests.
     *
     * @return Product
     */
    protected function createProduct()
    {
        return Product::create([
            'name' => 'Gold Product',
            'id'   => $this->productId,
            'url'  => 'www.stripe.com/gold',
        ]);
    }
}
