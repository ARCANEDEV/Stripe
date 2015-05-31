<?php namespace Arcanedev\Stripe\Tests\Utilities\Request;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Request\CurlClient;

class CurlClientTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const CURL_CLIENT_CLASS = 'Arcanedev\\Stripe\\Utilities\\Request\\CurlClient';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var CurlClient */
    private $curlClient;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->curlClient = new CurlClient($this->myApiKey, 'https://www.stripe.com');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->curlClient);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::CURL_CLIENT_CLASS, $this->curlClient);
    }

    /** @test */
    public function it_can_encode_array_to_string_query()
    {
        $method = $this->getCurlClientMethod('encode');

        $this->assertEquals(
            'my=value&that%5Byour%5D=example&bar=1',
            $method->invoke($this->curlClient, [
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
            $method->invoke($this->curlClient, [
                'that' => [
                    'your'  => 'example',
                    'foo'   => null
                ]
            ])
        );

        $this->assertEquals(
            'that=example&foo%5B%5D=bar&foo%5B%5D=baz',
            $method->invoke($this->curlClient, [
                'that'  => 'example',
                'foo'   => ['bar', 'baz']
            ])
        );

        $this->assertEquals(
            'my=value&that%5Byour%5D%5B%5D=cheese&that%5Byour%5D%5B%5D=whiz&bar=1',
            $method->invoke($this->curlClient, [
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
            $method->invoke($this->curlClient, [
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
        return parent::getMethod(self::CURL_CLIENT_CLASS, $method);
    }
}
