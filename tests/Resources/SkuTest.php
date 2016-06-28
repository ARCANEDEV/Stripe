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
    public function it_can_get_all()
    {
        $skus = Sku::all(['include' => ['total_count']]);

        $this->assertSame('/v1/skus', $skus->url);
        $this->assertInstanceOf('Arcanedev\Stripe\Collection', $skus);
        $this->assertTrue($skus->isList());

        if ($skus->count() > 0) {
            $this->assertSame($skus->count(), $skus->total_count);
            $this->assertInstanceOf('Arcanedev\Stripe\Resources\Sku', $skus->data[0]);
        }
    }

    /** @test */
    public function it_retrieve()
    {
        $sku       = $this->createSku();
        $stripeSku = Sku::retrieve($sku->id);

        $this->assertSame($sku->price,               $stripeSku->price);
        $this->assertSame($sku->inventory->type,     $stripeSku->inventory->type);
        $this->assertSame($sku->inventory->quantity, $stripeSku->inventory->quantity);
    }

    /** @test */
    public function it_can_create()
    {
        $sku = $this->createSku();

        $this->assertSame(500,      $sku->price);
        $this->assertSame(40,       $sku->inventory->quantity);
        $this->assertSame('finite', $sku->inventory->type);
    }

    /** @test */
    public function it_can_update()
    {
        $sku = $this->createSku();
        $sku = Sku::update($sku->id, [
            'price' => 600
        ]);

        $this->assertSame(600,      $sku->price);
        $this->assertSame('finite', $sku->inventory->type);
        $this->assertSame(40,       $sku->inventory->quantity);
    }

    /** @test */
    public function it_can_save()
    {
        $sku = $this->createSku();
        $sku->price = 600;
        $sku->save();

        $this->assertSame(600,      $sku->price);
        $this->assertSame('finite', $sku->inventory->type);
        $this->assertSame(40,       $sku->inventory->quantity);
    }

    /** @test */
    public function it_can_delete()
    {
        $sku        = $this->createSku();
        $deletedSku = $sku->delete();

        $this->assertSame($sku->id, $deletedSku->id);
        $this->assertTrue($deletedSku->deleted);
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

        return Sku::create([
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
            'id'   => 'silver-'.self::generateRandomString(20),
            'url'  => 'www.stripe.com/silver'
        ]);
    }
}
