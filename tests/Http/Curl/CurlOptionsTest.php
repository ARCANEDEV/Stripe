<?php namespace Arcanedev\Stripe\Tests\Http\Curl;

use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Http\Curl\CurlOptions;

/**
 * Class     CurlOptionsTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities\Request
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurlOptionsTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var CurlOptions */
    private $curlOptions;

    private $url     = '';
    private $headers = [];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        Stripe::setVerifySslCerts(true);

        $this->curlOptions = new CurlOptions;

        $this->url     = 'https://www.stripe.com';
        $this->headers = [
            "X-Stripe-Client-User-Agent: my-user-agent",
            "User-Agent: Stripe/v1 PhpBindings/1.18.0",
            "Authorization: Bearer " . $this->myApiKey,
            "Content-Type: application/x-www-form-urlencoded",
        ];
    }

    public function tearDown()
    {
        unset($this->curlOptions);
        $this->url     = '';
        $this->headers = [];

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
            'Arcanedev\\Stripe\\Http\\Curl\\CurlOptions',
            $this->curlOptions
        );
        $this->assertEmpty($this->curlOptions->get());
    }

    /** @test */
    public function it_can_make_get_options()
    {
        $method = 'GET';

        $this->curlOptions->make($method, $this->url, '', $this->headers);

        $options = $this->curlOptions->get();
        $this->assertEquals(7, count($options));

        $this->assertArrayHasKey(CURLOPT_HTTPGET, $options);
        $this->assertTrue($options[CURLOPT_HTTPGET]);

        $this->assertArrayHasKey(CURLOPT_CUSTOMREQUEST, $options);
        $this->assertEquals($method, $options[CURLOPT_CUSTOMREQUEST]);

        $this->assertDefaultOptions($options);
    }

    /** @test */
    public function it_can_make_get_options_with_ssl_enabled()
    {
        Stripe::setVerifySslCerts(false);

        $method = 'GET';
        $this->curlOptions->make($method, $this->url, '', $this->headers);

        $options = $this->curlOptions->get();
        $this->assertEquals(8, count($options));

        $this->assertArrayHasKey(CURLOPT_HTTPGET, $options);
        $this->assertTrue($options[CURLOPT_HTTPGET]);

        $this->assertArrayHasKey(CURLOPT_CUSTOMREQUEST, $options);
        $this->assertEquals($method, $options[CURLOPT_CUSTOMREQUEST]);

        $this->assertArrayHasKey(CURLOPT_SSL_VERIFYPEER, $options);
        $this->assertFalse($options[CURLOPT_SSL_VERIFYPEER]);

        $this->assertDefaultOptions($options);
    }

    /** @test */
    public function it_can_make_post_options()
    {
        $method = 'POST';
        $params = 'foo=bar&bar=foo';

        $this->curlOptions->make($method, $this->url, $params, $this->headers);

        $options = $this->curlOptions->get();
        $this->assertEquals(8, count($options));

        $this->assertArrayHasKey(CURLOPT_POST, $options);
        $this->assertTrue($options[CURLOPT_POST]);

        $this->assertArrayHasKey(CURLOPT_CUSTOMREQUEST, $options);
        $this->assertEquals($method, $options[CURLOPT_CUSTOMREQUEST]);

        $this->assertArrayHasKey(CURLOPT_POSTFIELDS, $options);
        $this->assertEquals($params, $options[CURLOPT_POSTFIELDS]);

        $this->assertDefaultOptions($options);
    }

    /** @test */
    public function it_can_make_delete_options()
    {
        $method = 'DELETE';
        $params = 'foo=bar&bar=foo';

        $this->curlOptions->make($method, $this->url, $params, $this->headers);

        $options = $this->curlOptions->get();
        $this->assertEquals(6, count($options));

        $this->assertArrayHasKey(CURLOPT_CUSTOMREQUEST, $options);
        $this->assertEquals($method, $options[CURLOPT_CUSTOMREQUEST]);

        $this->assertDefaultOptions($options);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     * @expectedExceptionCode    500
     * @expectedExceptionMessage The method must be a string value, NULL given
     */
    public function it_must_throw_invalid_argument_exception_on_method()
    {
        $this->curlOptions->make(null, $this->url, '', $this->headers);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\BadMethodCallException
     * @expectedExceptionCode    405
     * @expectedExceptionMessage The method [PUT] is not allowed
     */
    public function it_must_throw_bad_method_call_exception_on_method()
    {
        $this->curlOptions->make('PUT', $this->url, '', $this->headers);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode    500
     * @expectedExceptionMessage Issuing a GET request with a file parameter
     */
    public function it_must_throw_api_exception_on_posting_file_with_get_method()
    {
        $this->curlOptions->make('GET', $this->url, '', $this->headers, true);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Assert Default options
     *
     * @param array  $options
     */
    private function assertDefaultOptions($options)
    {
        $this->assertArrayHasKey(CURLOPT_URL, $options);
        $this->assertEquals($this->url, $options[ CURLOPT_URL ]);

        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $options);
        $this->assertTrue($options[ CURLOPT_RETURNTRANSFER ]);

        $this->assertArrayHasKey(CURLOPT_CONNECTTIMEOUT, $options);
        $this->assertEquals(30, $options[ CURLOPT_CONNECTTIMEOUT ]);

        $this->assertArrayHasKey(CURLOPT_TIMEOUT, $options);
        $this->assertEquals(80, $options[ CURLOPT_TIMEOUT ]);

        $this->assertArrayHasKey(CURLOPT_HTTPHEADER, $options);
        $this->assertEquals($this->headers, $options[ CURLOPT_HTTPHEADER ]);
    }
}
