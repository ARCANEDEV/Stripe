<?php namespace Arcanedev\Stripe\Tests\Http\Curl;

use Arcanedev\Stripe\Http\Curl\HttpClient;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     HttpClientTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities\Request
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HttpClientTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Http\Curl\HttpClient */
    private $httpClient;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->httpClient = HttpClient::instance();
    }

    public function tearDown()
    {
        unset($this->httpClient);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Http\\Curl\\HttpClient',
            $this->httpClient
        );
    }

    /** @test */
    public function it_can_encode_array_to_string_query()
    {
        $method = $this->getCurlClientMethod('encode');

        $this->assertSame(
            'my=value&that%5Byour%5D=example&bar=1',
            $method->invoke($this->httpClient, [
                'my'   => 'value',
                'that' => ['your' => 'example'],
                'bar'  => 1,
                'baz'  => null
            ])
        );

        $this->assertSame(
            'that%5Byour%5D=example',
            $method->invoke($this->httpClient, [
                'that' => [
                    'your' => 'example',
                    'foo'  => null,
                ],
            ])
        );

        $this->assertSame(
            'that=example&foo%5B%5D=bar&foo%5B%5D=baz',
            $method->invoke($this->httpClient, [
                'that' => 'example',
                'foo'  => ['bar', 'baz'],
            ])
        );

        $this->assertSame(
            'my=value&that%5Byour%5D%5B%5D=cheese&that%5Byour%5D%5B%5D=whiz&bar=1',
            $method->invoke($this->httpClient, [
                'my'   => 'value',
                'that' => [
                    'your' => ['cheese', 'whiz', null],
                ],
                'bar'  => 1,
                'baz'  => null
            ])
        );

        // Ignores an empty array
        $this->assertSame(
            'bar=baz',
            $method->invoke($this->httpClient, [
                'foo' => [],
                'bar' => 'baz',
            ])
        );

        $this->assertSame(
            'foo%5B0%5D%5Bbar%5D=baz&foo%5B1%5D%5Bbar%5D=bin',
            $method->invoke($this->httpClient, [
                'foo' => [
                    ['bar' => 'baz'],
                    ['bar' => 'bin'],
                ],
            ])
        );
    }

    /** @test */
    public function it_can_set_and_get_timeout_and_connect_timeout()
    {
        $curl = HttpClient::instance();

        $this->assertSame($this->timeout,                      $curl->getTimeout());
        $this->assertSame(HttpClient::DEFAULT_CONNECT_TIMEOUT, $curl->getConnectTimeout());

        $curl->setTimeout(10)->setConnectTimeout(1);

        $this->assertSame(1,  $curl->getConnectTimeout());
        $this->assertSame(10, $curl->getTimeout());

        $curl->setTimeout(-1)->setConnectTimeout(-999);

        $this->assertSame(0, $curl->getTimeout());
        $this->assertSame(0, $curl->getConnectTimeout());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Curl Client reflected method.
     *
     * @param  string  $method
     *
     * @return \ReflectionMethod
     */
    private function getCurlClientMethod($method)
    {
        return parent::getMethod('Arcanedev\\Stripe\\Http\\Curl\\HttpClient', $method);
    }
}
