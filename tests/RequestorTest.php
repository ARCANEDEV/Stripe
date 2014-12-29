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
     */
    public function testCanBlockBlacklistedPEMCert()
    {
        $cert = $this->getBlackListedCert();

        $this->assertTrue(Requestor::isBlackListed($cert));
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get a black listed Cert by Stripe
     *
     * @return string
     */
    private function getBlackListedCert()
    {
        return // {{{ Revoked certificate from api.stripe.com
            '-----BEGIN CERTIFICATE-----
MIIGoDCCBYigAwIBAgIQATGh1aL1Q3mXYwp7zTQ8+zANBgkqhkiG9w0BAQUFADBm
MQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3
d3cuZGlnaWNlcnQuY29tMSUwIwYDVQQDExxEaWdpQ2VydCBIaWdoIEFzc3VyYW5j
ZSBDQS0zMB4XDTEzMDkyNzAwMDAwMFoXDTE1MDEwODEyMDAwMFowajELMAkGA1UE
BhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExFjAUBgNVBAcTDVNhbiBGcmFuY2lz
Y28xFTATBgNVBAoTDFN0cmlwZSwgSW5jLjEXMBUGA1UEAxMOYXBpLnN0cmlwZS5j
b20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC9a37/epvqPM/9ExSv
L4jOFyuT+h9+kSePtjRD4N2z/r9zqUt88TRe2TSPM0o7yqRAmggqck1iFQmmgkU8
i5YjaGBVUSp9jyWZ7U+G9L9IRmxxWoYKaofpnGiGuTnpWgPPYtooXx+mhatvmiiM
tdJCU5QCN4rSvH9QnMHGrGupSw0Hb68d5nbbfk5f3IdYpjFR0+b0RHIoSrYPhiJF
r3/4h61Iu3PFea70wASLnP0olKlstQ6FONpsoYBRONvgs8/gUPQVY/VllbEceEpt
Bm5fIP5Cgd+Zya9uGqXsru1MyPIrR93u/YHDSYpC1TJ+BlSAamoC8ahtRNXLueRM
OFn5AgMBAAGjggNEMIIDQDAfBgNVHSMEGDAWgBRQ6nOJ2yn7EI+e5QEg1N55mUiD
9zAdBgNVHQ4EFgQUVhDDlYPPyDwTr1hy5uUZYYHEJyYwGQYDVR0RBBIwEIIOYXBp
LnN0cmlwZS5jb20wDgYDVR0PAQH/BAQDAgWgMB0GA1UdJQQWMBQGCCsGAQUFBwMB
BggrBgEFBQcDAjBhBgNVHR8EWjBYMCqgKKAmhiRodHRwOi8vY3JsMy5kaWdpY2Vy
dC5jb20vY2EzLWcyNC5jcmwwKqAooCaGJGh0dHA6Ly9jcmw0LmRpZ2ljZXJ0LmNv
bS9jYTMtZzI0LmNybDCCAcQGA1UdIASCAbswggG3MIIBswYJYIZIAYb9bAEBMIIB
pDA6BggrBgEFBQcCARYuaHR0cDovL3d3dy5kaWdpY2VydC5jb20vc3NsLWNwcy1y
ZXBvc2l0b3J5Lmh0bTCCAWQGCCsGAQUFBwICMIIBVh6CAVIAQQBuAHkAIAB1AHMA
ZQAgAG8AZgAgAHQAaABpAHMAIABDAGUAcgB0AGkAZgBpAGMAYQB0AGUAIABjAG8A
bgBzAHQAaQB0AHUAdABlAHMAIABhAGMAYwBlAHAAdABhAG4AYwBlACAAbwBmACAA
dABoAGUAIABEAGkAZwBpAEMAZQByAHQAIABDAFAALwBDAFAAUwAgAGEAbgBkACAA
dABoAGUAIABSAGUAbAB5AGkAbgBnACAAUABhAHIAdAB5ACAAQQBnAHIAZQBlAG0A
ZQBuAHQAIAB3AGgAaQBjAGgAIABsAGkAbQBpAHQAIABsAGkAYQBiAGkAbABpAHQA
eQAgAGEAbgBkACAAYQByAGUAIABpAG4AYwBvAHIAcABvAHIAYQB0AGUAZAAgAGgA
ZQByAGUAaQBuACAAYgB5ACAAcgBlAGYAZQByAGUAbgBjAGUALjB7BggrBgEFBQcB
AQRvMG0wJAYIKwYBBQUHMAGGGGh0dHA6Ly9vY3NwLmRpZ2ljZXJ0LmNvbTBFBggr
BgEFBQcwAoY5aHR0cDovL2NhY2VydHMuZGlnaWNlcnQuY29tL0RpZ2lDZXJ0SGln
aEFzc3VyYW5jZUNBLTMuY3J0MAwGA1UdEwEB/wQCMAAwDQYJKoZIhvcNAQEFBQAD
ggEBAKPiwJIeR52VOjhPew9cx19nmkHXDxxPzcOmSsF3gk9jogXh61yA6DevcTBY
KTNUhkTRWujOUdwZqNuvaLCLwn/TEGV9hM4lOKah8yqCQB8PhT7baMiL7mltAhEE
SBs2soRGVXHr3AczRKLW3G+IbIpUc3vilOul/PXWHutfzz7/asxXSTk/siVKROQ8
/KWrujG6wopwEEGExhlYOYBuXObwoSCV2nqIgr92fpHGvbMIFKSICoT7RCm8EVcb
3PGuaL8B8TZVbTOPYoJHdPzeRxL8Rbg8sDogHR+jkqwwyhUCfuzVbOjWFJU1DKvr
CBoD8xKYd5r7CYf1Du+nNMmDmrE=
-----END CERTIFICATE-----';
        // }}}
    }

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
