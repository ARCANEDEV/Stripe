<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\RequestOptions;

class RequestOptionsTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const REQUESTOPTIONS_CLASS = 'Arcanedev\\Stripe\\Utilities\\RequestOptions';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

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
    public function it_can_be_instantiated()
    {
        $opts = new RequestOptions('', []);

        $this->assertInstanceOf(self::REQUESTOPTIONS_CLASS, $opts);
        $this->assertFalse($opts->hasApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_api_key_string()
    {
        $opts = RequestOptions::parse("foo");

        $this->assertTrue($opts->hasApiKey());
        $this->assertEquals("foo", $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_api_key_array()
    {
        $opts = RequestOptions::parse([
            'api_key' => 'foo',
        ]);

        $this->assertEquals('foo', $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_null()
    {
        $opts = RequestOptions::parse(null);

        $this->assertEquals(null, $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_empty_array()
    {
        $opts = RequestOptions::parse([]);
        $this->assertEquals(null, $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_idempotent_key_array()
    {
        $opts = RequestOptions::parse([
            'idempotency_key' => 'foo',
        ]);

        $this->assertEquals(null, $opts->getApiKey());
        $this->assertEquals(['Idempotency-Key' => 'foo'], $opts->getHeaders());
    }

    /** @test */
    public function it_can_parse_key_array()
    {
        $opts = RequestOptions::parse([
            'idempotency_key' => 'foo',
            'api_key'         => 'foo'
        ]);

        $this->assertEquals('foo', $opts->getApiKey());
        $this->assertEquals(['Idempotency-Key' => 'foo'], $opts->getHeaders());
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_on_wrong_type()
    {
        RequestOptions::parse(5);
    }
}
