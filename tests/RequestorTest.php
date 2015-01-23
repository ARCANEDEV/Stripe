<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resources\Customer;
use ReflectionClass;

class RequestorTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Requestor */
    private $requestor;

    const REQUESTOR_CLASS = 'Arcanedev\\Stripe\\Requestor';

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
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf(self::REQUESTOR_CLASS, $this->requestor);
    }

    /**
     * @test
     */
    public function testCanEncodeObjects()
    {
        // We have to do some work here because this is normally private.
        // This is just for testing! Also it only works on PHP >= 5.3
        if (version_compare(PHP_VERSION, '5.3.2', '>=')) {
            $method = $this->getMethod('encodeObjects');

            $this->assertEquals(['customer' => 'abcd'], $method->invoke(null, [
                'customer' => new Customer('abcd')
            ]));

            // Preserves UTF-8
            $v      = ['customer' => "☃"];
            $this->assertEquals($v, $method->invoke(null, $v));

            // Encodes latin-1 -> UTF-8
            $v      = ['customer' => "\xe9"];
            $this->assertEquals(['customer' => "\xc3\xa9"], $method->invoke(null, $v));
        }
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     */
    public function testMustThrowApiKeyNotSetExceptionOnEmptyApiKey()
    {
        $method = $this->getMethod('checkApiKey');

        $method->invoke(new Requestor('  '));
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function testMustThrowApiExceptionOnInvalidMethod()
    {
        $method = $this->getMethod('checkMethod');

        $method->invoke($this->requestor, 'PUT');
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode    500
     * @expectedExceptionMessage Invalid response body from API: {bad: data} (HTTP response code was 200)
     */
    public function testMustThrowApiExceptionOnInvalidResponse()
    {
        $method = $this->getMethod('interpretResponse');

        $method->invoke($this->requestor, '{bad: data}', 200);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $methodName
     *
     * @return \ReflectionMethod
     */
    private function getMethod($methodName)
    {
        $reflector = new ReflectionClass(self::REQUESTOR_CLASS);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
