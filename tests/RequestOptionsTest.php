<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\RequestOptions;

class RequestOptionsTest extends StripeTestCase
{
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
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $opts = new RequestOptions('', []);
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\RequestOptions',
            $opts
        );
        $this->assertFalse($opts->hasApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /**
     * @test
     */
    public function testCanParseAPIKeyString()
    {
        $opts = RequestOptions::parse("foo");

        $this->assertTrue($opts->hasApiKey());
        $this->assertEquals("foo", $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /**
     * @test
     */
    public function testCanParseAPIKeyArray()
    {
        $opts = RequestOptions::parse([
            'api_key' => 'foo',
        ]);

        $this->assertEquals('foo', $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /**
     * @test
     */
    public function testCanParseNull()
    {
        $opts = RequestOptions::parse(null);

        $this->assertEquals(null, $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /**
     * @test
     */
    public function testCanParseEmptyArray()
    {
        $opts = RequestOptions::parse([]);
        $this->assertEquals(null, $opts->getApiKey());
        $this->assertEquals([], $opts->getHeaders());
    }

    /**
     * @test
     */
    public function testCanParseIdempotentKeyArray()
    {
        $opts = RequestOptions::parse([
            'idempotency_key' => 'foo',
        ]);

        $this->assertEquals(null, $opts->getApiKey());
        $this->assertEquals(['Idempotency-Key' => 'foo'], $opts->getHeaders());
    }

    /**
     * @test
     */
    public function testCanParseKeyArray()
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
    public function testMustThrowApiExceptionOnWrongType()
    {
        RequestOptions::parse(5);
    }
}
