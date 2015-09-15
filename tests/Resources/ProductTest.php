<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Product;
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
        parent::tearDown();

        $this->productId = '';
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
