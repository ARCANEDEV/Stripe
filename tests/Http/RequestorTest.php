<?php namespace Arcanedev\Stripe\Tests\Http;

use Arcanedev\Stripe\Http\Curl\HttpClient;
use Arcanedev\Stripe\Http\Requestor;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     RequestorTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RequestorTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Http\Requestor */
    private $requestor;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->requestor = new Requestor;
    }

    public function tearDown()
    {
        unset($this->requestor);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Requestor::class, $this->requestor);
    }

    /** @test */
    public function it_can_encode_objects()
    {
        $method = self::getRequestMethod('encodeObjects');

        $this->assertSame(['customer' => 'abcd'], $method->invoke(null, [
            'customer' => new Customer('abcd'),
        ]));

        // Preserves UTF-8
        $value  = ['customer' => "☃"];
        $this->assertSame($value, $method->invoke(null, $value));

        // Encodes latin-1 -> UTF-8
        $value  = ['customer' => "\xe9"];
        $this->assertSame(['customer' => "\xc3\xa9"], $method->invoke(null, $value));
    }

    /**
     * @test
     *
     * @expectedException  \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     */
    public function it_must_throw_api_key_not_set_exception_on_empty_api_key()
    {
        $method = self::getRequestMethod('checkApiKey');

        $method->invokeArgs(new Requestor('  '), []);
    }

    /** @test */
    public function it_must_throw_api_exception_on_invalid_method()
    {
        if (version_compare(PHP_VERSION, '7.0', '>=')) {
            $this->markTestSkipped(
                'Skipped because it throws ReflectionException on PHP 7.'
            );
        }
        else {
            $method = self::getRequestMethod('checkMethod');

            $thrown = false;
            try {
                $method->invokeArgs(new Requestor, ['PUT']);
            }
            catch(\Arcanedev\Stripe\Exceptions\ApiException $e) {
                $thrown = true;
                $this->assertSame(
                    'Unrecognized method put, must be [get, post, delete].',
                    $e->getMessage()
                );
            }

            $this->assertTrue($thrown);
        }
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode     500
     * @expectedExceptionMessage  Invalid response body from API: {bad: data} (HTTP response code was 200, json_last_error() was 4)
     */
    public function it_must_throw_api_exception_on_invalid_response()
    {
        $method = self::getRequestMethod('interpretResponse');

        $method->invokeArgs(new Requestor, ['{bad: data}', 200, []]);
    }

    /** @test */
    public function it_can_inject_the_http_client()
    {
        $method = self::getRequestMethod('httpClient');
        $curl   = HttpClient::instance();

        $curl->setTimeout(10);
        Requestor::setHttpClient($curl);

        $this->assertSame($method->invoke(new Requestor), $curl);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the Requestor reflected method.
     *
     * @param  string  $method
     *
     * @return \ReflectionMethod
     */
    private function getRequestMethod($method)
    {
        return parent::getMethod(Requestor::class, $method);
    }
}
