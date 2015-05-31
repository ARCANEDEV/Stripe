<?php namespace Arcanedev\Stripe\Tests\Utilities\Request;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Request\HttpClient;

class CurlClientTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const HTTP_CLIENT_CLASS = 'Arcanedev\\Stripe\\Utilities\\Request\\HttpClient';

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
        parent::tearDown();

        unset($this->httpClient);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::HTTP_CLIENT_CLASS, $this->httpClient);
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Curl Client method
     *
     * @param string $method
     *
     * @return \ReflectionMethod
     */
    private function getCurlClientMethod($method)
    {
        return parent::getMethod(self::HTTP_CLIENT_CLASS, $method);
    }
}
