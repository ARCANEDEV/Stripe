<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\ThreeDSecure;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ThreeDSecureTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ThreeDSecureTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_retrieve()
    {
        $return = [
            'id'     => 'tdsrc_test',
            'object' => 'three_d_secure',
        ];

        $this->mockRequest('GET', '/v1/3d_secure/tdsrc_test', [], $return);

        $threeDSecure = ThreeDSecure::retrieve($return['id']);

        $this->assertInstanceOf(ThreeDSecure::class, $threeDSecure);
        $this->assertSame($return['id'],     $threeDSecure->id);
        $this->assertSame($return['object'], $threeDSecure->object);
    }

    /** @test */
    public function it_can_create()
    {
        $params = [
            'card'       => 'tok_test',
            'amount'     => 1500,
            'currency'   => 'usd',
            'return_url' => 'https://example.org/3d-secure-result',
        ];

        $this->mockRequest('POST', '/v1/3d_secure', $params, [
            'id'     => 'tdsrc_test',
            'object' => 'three_d_secure',
        ]);

        $threeDSecure = ThreeDSecure::create($params);

        $this->assertInstanceOf(ThreeDSecure::class, $threeDSecure);
        $this->assertSame('tdsrc_test', $threeDSecure->id);
    }
}
