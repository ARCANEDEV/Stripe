<?php namespace Arcanedev\Stripe\Tests\Http;

use Arcanedev\Stripe\Http\RequestOptions;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     RequestOptionsTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RequestOptionsTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $opts = new RequestOptions('', []);

        $this->assertInstanceOf('Arcanedev\\Stripe\\Http\\RequestOptions', $opts);
        $this->assertFalse($opts->hasApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_api_key_string()
    {
        $opts = RequestOptions::parse('foo');

        $this->assertTrue($opts->hasApiKey());
        $this->assertEquals('foo', $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_api_key_array()
    {
        $opts = RequestOptions::parse(['api_key' => 'foo']);

        $this->assertEquals('foo', $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_null()
    {
        $opts = RequestOptions::parse(null);

        $this->assertEmpty($opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_empty_array()
    {
        $opts = RequestOptions::parse([]);

        $this->assertNull($opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_idempotent_key_array()
    {
        $opts = RequestOptions::parse(['idempotency_key' => 'foo']);

        $this->assertNull($opts->getApiKey());
        $this->assertEquals(['Idempotency-Key' => 'foo'], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_key_array()
    {
        $opts = RequestOptions::parse([
            'idempotency_key' => 'key-foo',
            'api_key'         => 'api-foo',
        ]);

        $this->assertEquals('api-foo', $opts->getApiKey());
        $this->assertEquals(['Idempotency-Key' => 'key-foo'], $opts->getHeaders());
    }

    /**
     * @test
     *
     * @expectedException  \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_on_wrong_type()
    {
        RequestOptions::parse(5);
    }
}
