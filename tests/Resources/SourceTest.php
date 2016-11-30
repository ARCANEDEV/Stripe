<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Source;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     SourceTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SourceTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_retrieve()
    {
        $this->mockRequest('GET', '/v1/sources/src_foo', [], [
            'id'     => 'src_foo',
            'object' => 'source',
        ]);

        $source = Source::retrieve('src_foo');

        $this->assertSame($source->id, 'src_foo');
    }

    /** @test */
    public function it_can_create()
    {
        $this->mockRequest('POST', '/v1/sources',
            [
                'type'     => 'bitcoin',
                'amount'   => 1000,
                'currency' => 'usd',
                'owner'    => ['email' => 'jenny.rosen@example.com'],
            ],[
                'id'     => 'src_foo',
                'object' => 'source'
            ]);

        $source = Source::create([
            'type'     => 'bitcoin',
            'amount'   => 1000,
            'currency' => 'usd',
            'owner'    => ['email' => 'jenny.rosen@example.com'],
        ]);

        $this->assertSame($source->id, 'src_foo');
    }

    /** @test */
    public function it_can_verify()
    {
        $response = [
            'id' => 'src_foo',
            'object' => 'source',
            'verification' => ['status' => 'pending'],
        ];

        $this->mockRequest('GET', '/v1/sources/src_foo', [], $response);

        $response['verification']['status'] = 'succeeded';

        $this->mockRequest('POST', '/v1/sources/src_foo/verify', ['values' => [32, 45]], $response);

        $source = Source::retrieve('src_foo');

        $this->assertSame($source->verification->status, 'pending');

        $source->verify([
            'values' => [32, 45],
        ]);

        $this->assertSame($source->verification->status, 'succeeded');
    }
}
