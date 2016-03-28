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
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_create_sku()
    {
        $sku = $this->createSku();

        $this->assertEquals(500,      $sku->price);
        $this->assertEquals(40,       $sku->inventory->quantity);
        $this->assertEquals('finite', $sku->inventory->type);
    }

    /** @test */
    public function it_can_update_sku()
    {
        $sku = $this->createSku();
        $sku->price = 600;
        $sku->save();

        $this->assertEquals(600,      $sku->price);
        $this->assertEquals('finite', $sku->inventory->type);
        $this->assertEquals(40,       $sku->inventory->quantity);
    }

    /** @test */
    public function it_retrieve_sku()
    {
        $sku       = $this->createSku();
        $stripeSku = SKU::retrieve($sku->id);

        $this->assertEquals($sku->price,               $stripeSku->price);
        $this->assertEquals($sku->inventory->type,     $stripeSku->inventory->type);
        $this->assertEquals($sku->inventory->quantity, $stripeSku->inventory->quantity);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a SKU.
     *
     * @return \Arcanedev\Stripe\Resources\Sku
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
     * @return \Arcanedev\Stripe\Resources\Product
     */
    protected function createProduct()
    {
        return Product::create([
            'name' => 'Silver Product',
            'id'   => 'silver-' . self::generateRandomString(20),
            'url'  => 'www.stripe.com/silver'
        ]);
    }
}
