<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\GuzzleClient;

class GuzzleClientTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const GUZZLE_CLASS = 'Arcanedev\\Stripe\\Utilities\\GuzzleClient';
    /** @var GuzzleClient */
    private $guzzle;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->guzzle = new GuzzleClient;
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
        $this->assertInstanceOf(self::GUZZLE_CLASS, $this->guzzle);
    }

    /**
     * @test
     */
    public function testCanGetRequestWithoutParams()
    {
        $url      = 'http://httpbin.org/get';
        $response = $this->guzzle->get($url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals($url, $response->json()['url']);
    }

    public function testCanGetRequestWithParams()
    {
        $url      = 'http://httpbin.org/get';
        $params   = ['hello' => 'world'];
        $response = $this->guzzle->get($url, $params);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals($url . '?' . http_build_query($params), $response->json()['url']);
        $this->assertEquals($params, $response->json()['args']);
    }

    public function testCanPostRequestWithoutParams()
    {
        $url      = 'http://httpbin.org/post';
        $response = $this->guzzle->post($url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals($url, $response->json()['url']);
    }

    public function testCanPostRequestWithParams()
    {
        $url      = 'http://httpbin.org/post';
        $params   = ['hello' => 'world'];
        $response = $this->guzzle->post($url, $params);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals($url . '?' . http_build_query($params), $response->json()['url']);
        $this->assertEquals($params, $response->json()['args']);
    }

    public function testCanDeleteRequestWithoutParams()
    {
        $url      = 'http://httpbin.org/delete';
        $response = $this->guzzle->delete($url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals($url, $response->json()['url']);
    }

    public function testCanDeleteRequestWithParams()
    {
        $url      = 'http://httpbin.org/delete';
        $params   = ['hello' => 'world'];
        $response = $this->guzzle->delete($url, $params);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals($url . '?' . http_build_query($params), $response->json()['url']);
        $this->assertEquals($params, $response->json()['args']);
    }
}
