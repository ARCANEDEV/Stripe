<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Order;
use Arcanedev\Stripe\Resources\Product;
use Arcanedev\Stripe\Resources\Sku;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     OrderTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class OrderTest extends StripeTestCase
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
    public function it_can_create_order()
    {
        $order = $this->createOrder();

        $this->assertEquals('order', $order->object);
        $this->assertEquals('usd', $order->currency);
        $this->assertEquals('foo@bar.com', $order->email);
    }

    /** @test */
    public function it_can_update_order()
    {
        $order = $this->createOrder();

        $order->metadata->foo = 'bar';
        $order->save();

        $this->assertSame($order->metadata->foo, 'bar');
    }

    /** @test */
    public function it_can_retrieve_order()
    {
        $order       = $this->createOrder();
        $stripeOrder = Order::retrieve($order->id);

        $this->assertEquals($stripeOrder->id, $order->id);
        $this->assertEquals($stripeOrder->object, 'order');
    }

    /** @test */
    public function it_can_pay_order()
    {
        $order = $this->createOrder();
        $card  = [
            'object'    => 'card',
            'number'    => '4242424242424242',
            'exp_month' => '05',
            'exp_year'  => '2017'
        ];

        $order->pay([
            'source' => $card,
        ]);

        $this->assertEquals($order->status, 'paid');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create order for tests.
     *
     * @return Order
     */
    protected function createOrder()
    {
        $sku = $this->createSku();

        return Order::create([
            'items' => [
                [
                    'type' => 'sku',
                    'parent' => $sku->id,
                ],
            ],
            'currency' => 'usd',
            'email'    => 'foo@bar.com',
        ]);
    }

    /**
     * Create SKU for tests.
     *
     * @return Sku
     */
    protected function createSku()
    {
        $product = Product::create([
            'name'      => 'Silver Product',
            'id'        => 'silver-' . self::generateRandomString(20),
            'url'       => 'www.stripe.com/silver',
            'shippable' => false,
        ]);

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
}
