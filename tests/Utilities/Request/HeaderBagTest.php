<?php namespace Arcanedev\Stripe\Tests\Utilities\Request;

use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Request\HeaderBag;

class HeaderBagTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const HEADERBAG_CLASS = 'Arcanedev\\Stripe\\Utilities\\Request\\HeaderBag';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var HeaderBag */
    private $headerBag;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->headerBag = new HeaderBag;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->headerBag);

        Stripe::setApiVersion(null);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::HEADERBAG_CLASS, $this->headerBag);
    }

    /** @test */
    public function it_can_make_headers()
    {
        $headers = $this->headerBag->make($this->myApiKey);

        $this->assertEquals(4, count($headers));
        $this->assertEquals('User-Agent: Stripe/v1 PhpBindings/1.18.0', $headers[1]);
        $this->assertEquals('Authorization: Bearer ' . $this->myApiKey, $headers[2]);
        $this->assertEquals('Content-Type: application/x-www-form-urlencoded', $headers[3]);

        Stripe::setApiVersion($this->myApiVersion);
        $headers = $this->headerBag->make($this->myApiKey, [], true);

        $this->assertEquals(5, count($headers));
        $this->assertEquals('User-Agent: Stripe/v1 PhpBindings/1.18.0', $headers[1]);
        $this->assertEquals('Authorization: Bearer ' . $this->myApiKey, $headers[2]);
        $this->assertEquals('Content-Type: multipart/form-data', $headers[3]);
        $this->assertEquals('Stripe-Version: ' . $this->myApiVersion, $headers[4]);
    }

    /** @test */
    public function it_can_count_headers()
    {
        $this->headerBag->prepare($this->myApiKey);

        $this->assertEquals(4, $this->headerBag->count());
    }

    /** @test */
    public function it_can_add_header_to_headers_collection()
    {
        $this->headerBag->prepare($this->myApiKey);

        $this->assertEquals(4, $this->headerBag->count());

        $key   = 'X-Stripe-Client-Info';
        $value = '{"ca":"using Stripe-supplied CA bundle"}';
        $this->headerBag->set($key, $value);

        $this->assertEquals(5, $this->headerBag->count());
        $this->assertArrayHasKey($key, $this->headerBag->toArray());
        $this->assertEquals($key . ': ' . $value, $this->headerBag->all()[4]);
    }

    /** @test */
    public function it_can_return_headers_array()
    {
        $headersArray = $this->headerBag->prepare($this->myApiKey)->toArray();

        $this->assertArrayHasKey('X-Stripe-Client-User-Agent', $headersArray);
        $this->assertArrayHasKey('User-Agent', $headersArray);
        $this->assertArrayHasKey('Authorization', $headersArray);
        $this->assertArrayHasKey('Content-Type', $headersArray);
        $this->assertArrayNotHasKey('Stripe-Version', $headersArray);

        Stripe::setApiVersion($this->myApiVersion);
        $headersArray = $this->headerBag->prepare($this->myApiKey)->toArray();

        $this->assertArrayHasKey('X-Stripe-Client-User-Agent', $headersArray);
        $this->assertArrayHasKey('User-Agent', $headersArray);
        $this->assertArrayHasKey('Authorization', $headersArray);
        $this->assertArrayHasKey('Content-Type', $headersArray);
        $this->assertArrayHasKey('Stripe-Version', $headersArray);
    }

    /** @test */
    public function it_can_merge_headers()
    {
        $headers = $this->headerBag->make($this->myApiKey, [
            'Foo' => 'Bar',
        ]);

        $this->assertEquals(5, count($headers));
        $this->assertEquals('Foo: Bar', $headers[4]);
    }
}
