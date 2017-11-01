<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Resources\ExchangeRate;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ExchangeRateTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ExchangeRateTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_retrieve()
    {
        $this->mockRequest(
            'GET',
            '/v1/exchange_rates/usd',
            [],
            [
                'id'     => 'usd',
                'object' => 'exchange_rate',
                'rates'  => ['eur' => 0.845876],
            ]
        );


        $rate = ExchangeRate::retrieve($currency = 'usd');

        $this->assertInstanceOf(ExchangeRate::class, $rate);
        $this->assertSame('usd', $rate->id);
        $this->assertSame(0.845876, $rate->rates['eur']);
    }

    /** @test */
    public function it_can_list()
    {
        $this->mockRequest(
            'GET',
            '/v1/exchange_rates',
            [],
            [
                'object' => 'list',
                'data'   => [
                    [
                        'id'     => 'eur',
                        'object' => 'exchange_rate',
                        'rates'  => ['usd' => 1.18221],
                    ],
                    [
                        'id'     => 'usd',
                        'object' => 'exchange_rate',
                        'rates'  => ['eur' => 0.845876],
                    ],
                ],
            ]
        );

        $rates = ExchangeRate::all();

        $this->assertInstanceOf(Collection::class, $rates);
        $this->assertTrue(is_array($rates->data));

        $this->assertInstanceOf(ExchangeRate::class, $rates->data[0]);
    }
}
