<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\Order;
use Arcanedev\Stripe\Resources\OrderItem;
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
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_get_all()
    {
        $orders = Order::all(['include' => ['total_count']]);

        $this->assertInstanceOf(Collection::class, $orders);
        $this->assertTrue($orders->isList());

        if ($orders->count() > 0) {
            $this->assertSame($orders->total_count, $orders->count());
            $this->assertInstanceOf(Order::class, $orders->data[0]);
        }
    }

    /** @test */
    public function it_can_retrieve()
    {
        $order       = $this->createOrder();
        $stripeOrder = Order::retrieve($order->id);

        $this->assertSame($order->id, $stripeOrder->id);
        $this->assertSame('order',    $stripeOrder->object);
    }

    /** @test */
    public function it_can_create()
    {
        $order = $this->createOrder();

        $this->assertSame('order',       $order->object);
        $this->assertSame('usd',         $order->currency);
        $this->assertSame('foo@bar.com', $order->email);

        foreach ($order->items as $orderItem) {
            /** @var \Arcanedev\Stripe\Resources\OrderItem $orderItem */
            $this->assertInstanceOf(OrderItem::class, $orderItem);
            $this->assertTrue(in_array($orderItem->type, ['sku', 'tax', 'shipping', 'discount']));
        }
    }

    /** @test */
    public function it_can_update()
    {
        $order = $this->createOrder();
        $order = Order::update($order->id, [
            'metadata' => ['foo' => 'bar'],
        ]);

        $this->assertSame('bar', $order->metadata->foo);
    }

    /** @test */
    public function it_can_save()
    {
        $order = $this->createOrder();

        $order->metadata->foo = 'bar';
        $order->save();

        $this->assertSame('bar', $order->metadata->foo);
    }

    /** @test */
    public function it_can_pay_and_return()
    {
        $order = $this->createOrder();
        $card  = [
            'object'    => 'card',
            'number'    => '4242424242424242',
            'exp_month' => '05',
            'exp_year'  => date('Y') + 1,
        ];

        $order->pay(['source' => $card]);

        $this->assertSame('paid', $order->status);

        $orderReturn = $order->returnOrder();

        $this->assertSame($order->id, $orderReturn->order);
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
                'quantity' => 40,
            ],
            'product'   => $product->id
        ]);
    }
}
