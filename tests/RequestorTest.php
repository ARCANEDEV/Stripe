<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resources\Customer;

class RequestorTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const REQUESTOR_CLASS = 'Arcanedev\\Stripe\\Requestor';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Requestor */
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
        parent::tearDown();

        unset($this->requestor);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::REQUESTOR_CLASS, $this->requestor);
    }

    /** @test */
    public function it_can_encode_objects()
    {
        // We have to do some work here because this is normally private.
        // This is just for testing! Also it only works on PHP >= 5.3.2
        if (version_compare(PHP_VERSION, '5.3.2', '>=')) {
            $method = self::getRequestMethod('encodeObjects');

            $this->assertEquals(['customer' => 'abcd'], $method->invoke(null, [
                'customer' => new Customer('abcd')
            ]));

            // Preserves UTF-8
            $value  = ['customer' => "☃"];
            $this->assertEquals($value, $method->invoke(null, $value));

            // Encodes latin-1 -> UTF-8
            $value  = ['customer' => "\xe9"];
            $this->assertEquals(['customer' => "\xc3\xa9"], $method->invoke(null, $value));
        }
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     */
    public function it_must_throw_api_key_not_set_exception_on_empty_api_key()
    {
        $method = self::getRequestMethod('checkApiKey');

        $method->invokeArgs(new Requestor('  '), []);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_on_invalid_method()
    {
        $method = self::getRequestMethod('checkMethod');

        $method->invokeArgs($this->requestor, ['PUT']);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode    500
     * @expectedExceptionMessage Invalid response body from API: {bad: data} (HTTP response code was 200)
     */
    public function it_must_throw_api_exception_on_invalid_response()
    {
        $method = self::getRequestMethod('interpretResponse');

        $method->invokeArgs($this->requestor, ['{bad: data}', 200]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param  string $method
     *
     * @return \ReflectionMethod
     */
    private function getRequestMethod($method)
    {
        return parent::getMethod(self::REQUESTOR_CLASS, $method);
    }
}
