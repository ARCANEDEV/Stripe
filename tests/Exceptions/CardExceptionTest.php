<?php namespace Arcanedev\Stripe\Tests\Exceptions;

use Arcanedev\Stripe\Exceptions\CardException;
use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     CardExceptionTest
 *
 * @package  Arcanedev\Stripe\Tests\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CardExceptionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_get_code()
    {
        try {
            throw new CardException(
                // Message
                'hello',
                // Status Code
                400,
                // Stripe error type
                'some_param',
                // Stripe error code
                'some_code',
                // Response Body json
                "{'foo':'bar'}",
                // Response Body array
                ['foo' => 'bar']
            );
        }
        catch (CardException $e) {
            $this->assertSame('some_param', $e->getType());
            $this->assertSame('some_code', $e->getStripeCode());
        }
    }

    /** @test */
    public function it_must_decline()
    {
        try {
            Charge::create([
                'amount'   => 100,
                'currency' => 'usd',
                'card'     => [
                    'number' => '4000000000000002',
                    'exp_month' => '3',
                    'exp_year' => '2020',
                ]
            ]);
        }
        catch (CardException $e) {
            $this->assertSame(402, $e->getHttpStatus());
            $this->assertTrue(strpos($e->getRequestId(), 'req_') === 0, $e->getRequestId());
            $actual = $e->getJsonBody();
            $this->assertSame(
                [
                    'error' => [
                        'message' => 'Your card was declined.',
                        'type'    => 'card_error',
                        'code'    => 'card_declined',
                        'charge'  => $actual['error']['charge'],
                    ],
                ],
                $actual
            );
        }
    }
}
