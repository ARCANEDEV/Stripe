<?php namespace Arcanedev\Stripe\Tests\Http\Curl;

use Arcanedev\Stripe\Http\Curl\HeaderBag;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     HeaderBagTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities\Request
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HeaderBagTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Http\Curl\HeaderBag */
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
        unset($this->headerBag);

        Stripe::setApiVersion(null);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(HeaderBag::class, $this->headerBag);
    }

    /** @test */
    public function it_can_make_headers()
    {
        $headers = $this->headerBag->make($this->myApiKey);

        $this->assertCount(6, $headers);

        $this->assertSame("User-Agent: Stripe/v1 PhpBindings/{$this->myApiVersion}", $headers[1]);
        $this->assertSame("Authorization: Bearer {$this->myApiKey}",                 $headers[2]);
        $this->assertSame('Content-Type: application/x-www-form-urlencoded',         $headers[3]);
        $this->assertSame('Expect: ',                                                $headers[4]);
        $this->assertSame('Stripe-Version: 2017-04-06',                              $headers[5]);

        Stripe::setApiVersion($this->myApiVersion);
        $headers = $this->headerBag->make($this->myApiKey, [], true);

        $this->assertCount(6, $headers);
        $this->assertSame("User-Agent: Stripe/v1 PhpBindings/{$this->myApiVersion}", $headers[1]);
        $this->assertSame("Authorization: Bearer {$this->myApiKey}",                 $headers[2]);
        $this->assertSame('Content-Type: multipart/form-data',                       $headers[3]);
        $this->assertSame('Expect: ',                                                $headers[4]);
        $this->assertSame("Stripe-Version: {$this->myApiVersion}",                   $headers[5]);
    }

    /** @test */
    public function it_can_get_defaults()
    {
        $method = (new \ReflectionClass('Arcanedev\Stripe\Http\Curl\HeaderBag'))
            ->getMethod('getDefaults');
        $method->setAccessible(true);

        $headers = $method->invokeArgs($this->headerBag, [$this->myApiKey]);

        $this->assertCount(6, $headers);

        $this->assertSame("Stripe/v1 PhpBindings/{$this->myApiVersion}", $headers['User-Agent']);
        $this->assertSame("Bearer {$this->myApiKey}",                    $headers['Authorization']);
        $this->assertSame('application/x-www-form-urlencoded',           $headers['Content-Type']);
        $this->assertNull($headers['Expect']);

        Stripe::setApiVersion($this->myApiVersion);
        $headers = $method->invokeArgs($this->headerBag, [$this->myApiKey, true]);

        $this->assertCount(6, $headers);
        $this->assertSame("Stripe/v1 PhpBindings/{$this->myApiVersion}", $headers['User-Agent']);
        $this->assertSame("Bearer {$this->myApiKey}",                    $headers['Authorization']);
        $this->assertSame('multipart/form-data',                         $headers['Content-Type']);
        $this->assertSame($this->myApiVersion,                           $headers['Stripe-Version']);
        $this->assertNull($headers['Expect']);
    }

    /** @test */
    public function it_can_get_defaults_with_app_info()
    {
        $method = (new \ReflectionClass('Arcanedev\Stripe\Http\Curl\HeaderBag'))
            ->getMethod('getDefaults');
        $method->setAccessible(true);

        // no way to stub static methods with PHPUnit 4.x :(
        Stripe::setAppInfo('MyTestApp', '1.2.34', 'https://mytestapp.example');
        $headers = $method->invokeArgs($this->headerBag, [$this->myApiKey, true]);

        $ua = json_decode($headers['X-Stripe-Client-User-Agent']);
        $this->assertSame('MyTestApp', $ua->application->name);
        $this->assertSame('1.2.34', $ua->application->version);
        $this->assertSame('https://mytestapp.example', $ua->application->url);

        $this->assertSame(
            'Stripe/v1 PhpBindings/' . Stripe::VERSION . ' MyTestApp/1.2.34 (https://mytestapp.example)',
            $headers['User-Agent']
        );

        $this->assertSame('Bearer ' . $this->myApiKey, $headers['Authorization']);
    }

    /** @test */
    public function it_can_count_headers()
    {
        $this->headerBag->prepare($this->myApiKey);

        $this->assertSame(6, $this->headerBag->count());
    }

    /** @test */
    public function it_can_add_header_to_headers_collection()
    {
        $this->headerBag->prepare($this->myApiKey);

        $this->assertSame(6, $this->headerBag->count());

        $key   = 'X-Stripe-Client-Info';
        $value = '{"ca":"using Stripe-supplied CA bundle"}';
        $this->headerBag->set($key, $value);

        $this->assertSame(7, $this->headerBag->count());
        $this->assertArrayHasKey($key, $this->headerBag->toArray());
        $this->assertSame($key . ': ' . $value, $this->headerBag->get()[6]);
    }

    /** @test */
    public function it_can_return_headers_array()
    {
        $headersArray = $this->headerBag->prepare($this->myApiKey)->toArray();

        $this->assertArrayHasKey('X-Stripe-Client-User-Agent', $headersArray);
        $this->assertArrayHasKey('User-Agent', $headersArray);
        $this->assertArrayHasKey('Authorization', $headersArray);
        $this->assertArrayHasKey('Content-Type', $headersArray);
        $this->assertArrayHasKey('Stripe-Version', $headersArray);

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
        $headers = $this->headerBag->make($this->myApiKey, ['Foo' => 'Bar']);

        $this->assertCount(7, $headers);
        $this->assertSame('Foo: Bar', $headers[6]);
    }
}
