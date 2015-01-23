<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\SslChecker;

class SslCheckerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var SslChecker */
    private $sslChecker;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->sslChecker = new SslChecker;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->sslChecker);
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
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Utilities\\SslChecker',
            $this->sslChecker
        );
    }

    /**
     * @test
     */
    public function testCanSetAndGetUrl()
    {
        $url = 'http://www.stripe.com';

        $this->sslChecker->setUrl($url);

        $this->assertEquals(
            'ssl://www.stripe.com:443',
            $this->sslChecker->getUrl()
        );
    }

    /**
     * @test
     */
    public function testCanBlockBlacklistedPEMCert()
    {
        $cert = $this->getBlackListedCert();

        $this->assertTrue($this->sslChecker->isBlackListed($cert));
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
}
