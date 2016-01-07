<?php namespace Arcanedev\Stripe\Tests\Http\Curl;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Http\Curl\HttpClient;

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
    /** @var HttpClient */
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

        $this->assertEquals(
            'my=value&that%5Byour%5D=example&bar=1',
            $method->invoke($this->httpClient, [
                'my'    => 'value',
                'that'  => [
                    'your' => 'example'
                ],
                'bar'   => 1,
                'baz'   => null
            ])
        );

        $this->assertEquals(
            'that%5Byour%5D=example',
            $method->invoke($this->httpClient, [
                'that' => [
                    'your'  => 'example',
                    'foo'   => null
                ]
            ])
        );

        $this->assertEquals(
            'that=example&foo%5B%5D=bar&foo%5B%5D=baz',
            $method->invoke($this->httpClient, [
                'that'  => 'example',
                'foo'   => ['bar', 'baz']
            ])
        );

        $this->assertEquals(
            'my=value&that%5Byour%5D%5B%5D=cheese&that%5Byour%5D%5B%5D=whiz&bar=1',
            $method->invoke($this->httpClient, [
                'my'    => 'value',
                'that'  => [
                    'your' => ['cheese', 'whiz', null]
                ],
                'bar'   => 1,
                'baz'   => null
            ])
        );

        // Ignores an empty array
        $this->assertEquals(
            'bar=baz',
            $method->invoke($this->httpClient, [
                'foo' => [],
                'bar' => 'baz'
            ])
        );
    }

    /** @test */
    public function it_can_set_and_get_timeout_and_connect_timeout()
    {
        $curl = HttpClient::instance();

        $this->assertEquals($this->timeout,                      $curl->getTimeout());
        $this->assertEquals(HttpClient::DEFAULT_CONNECT_TIMEOUT, $curl->getConnectTimeout());

        $curl->setTimeout(10)->setConnectTimeout(1);

        $this->assertEquals(1,  $curl->getConnectTimeout());
        $this->assertEquals(10, $curl->getTimeout());

        $curl->setTimeout(-1)->setConnectTimeout(-999);

        $this->assertEquals(0, $curl->getTimeout());
        $this->assertEquals(0, $curl->getConnectTimeout());
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
