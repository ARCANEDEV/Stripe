<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Source;
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

    /** @test */
    public function it_can_save()
    {
        $response = [
            'id'       => 'src_foo',
            'object'   => 'source',
            'metadata' => [],
        ];

        $this->mockRequest(
            'GET',
            '/v1/sources/src_foo',
            [],
            $response
        );

        $response['metadata'] = ['foo' => 'bar'];

        $this->mockRequest(
            'POST',
            '/v1/sources/src_foo',
            [
                'metadata' => ['foo' => 'bar'],
            ],
            $response
        );

        $source = Source::retrieve('src_foo');
        $source->metadata['foo'] = 'bar';
        $source->save();

        $this->assertSame('bar', $source->metadata['foo']);
    }

    /** @test */
    public function it_can_save_owner()
    {
        $response = [
            'id'     => 'src_foo',
            'object' => 'source',
            'owner'  => [
                'name' => null, 'address' => null,
            ],
        ];

        $this->mockRequest('GET', '/v1/sources/src_foo', [], $response);

        $response['owner'] = [
            'name'    => 'Stripey McStripe',
            'address' => [
                'line1'       => 'Test Address',
                'city'        => 'Test City',
                'postal_code' => '12345',
                'state'       => 'Test State',
                'country'     => 'Test Country',
            ]
        ];

        $this->mockRequest(
            'POST',
            '/v1/sources/src_foo',
            [
                'owner' => [
                    'name'    => 'Stripey McStripe',
                    'address' => [
                        'line1'       => 'Test Address',
                        'city'        => 'Test City',
                        'postal_code' => '12345',
                        'state'       => 'Test State',
                        'country'     => 'Test Country',
                    ],
                ],
            ],
            $response
        );

        $source = Source::retrieve('src_foo');

        $source->owner['name']    = 'Stripey McStripe';
        $source->owner['address'] = [
            'line1'       => 'Test Address',
            'city'        => 'Test City',
            'postal_code' => '12345',
            'state'       => 'Test State',
            'country'     => 'Test Country',
        ];
        $source->save();

        $this->assertSame($source->owner['name'],                   'Stripey McStripe');
        $this->assertSame($source->owner['address']['line1'],       'Test Address');
        $this->assertSame($source->owner['address']['city'],        'Test City');
        $this->assertSame($source->owner['address']['postal_code'], '12345');
        $this->assertSame($source->owner['address']['state'],       'Test State');
        $this->assertSame($source->owner['address']['country'],     'Test Country');
    }

    /** @test */
    public function it_can_delete_attached_source()
    {
        $response = [
            'id'       => 'src_foo',
            'object'   => 'source',
            'customer' => 'cus_bar',
        ];
        $this->mockRequest('GET', '/v1/sources/src_foo', [], $response);

        unset($response['customer']);
        $this->mockRequest('DELETE', '/v1/customers/cus_bar/sources/src_foo', [], $response);

        $source = Source::retrieve('src_foo');
        $source->delete();

        $this->assertFalse(array_key_exists('customer', $source));
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_can_not_delete_unattached()
    {
        $response = [
            'id'     => 'src_foo',
            'object' => 'source',
        ];
        $this->mockRequest('GET', '/v1/sources/src_foo', [], $response);

        $source = Source::retrieve('src_foo');
        $source->delete();
    }
}
