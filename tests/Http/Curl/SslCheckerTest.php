<?php namespace Arcanedev\Stripe\Tests\Http\Curl;

use Arcanedev\Stripe\Http\Curl\SslChecker;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     SslCheckerTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities\Request
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SslCheckerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Http\Curl\SslChecker */
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
        unset($this->sslChecker);

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
            'Arcanedev\\Stripe\\Http\\Curl\\SslChecker',
            $this->sslChecker
        );
    }

    /** @test */
    public function it_can_set_and_get_url()
    {
        $url = 'http://www.stripe.com';

        $this->sslChecker->setUrl($url);

        $this->assertEquals(
            'ssl://www.stripe.com:443',
            $this->sslChecker->getUrl()
        );
    }

    /** @test */
    public function it_can_block_a_blacklisted_pem_cert()
    {
        $cert = $this->getBlackListedCert();

        $this->assertTrue($this->sslChecker->isBlackListed($cert));
    }

    /** @test */
    public function it_can_get_ca_bundle_path()
    {
        $certPath = SslChecker::caBundle();

        $this->assertNotEmpty($certPath);
        $this->assertFileExists($certPath);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiConnectionException
     */
    public function it_must_throw_api_connection_exception_on_blacklisted_pem_cert()
    {
        $cert = $this->getBlackListedCert();

        $this->sslChecker->checkBlackList($cert);
    }

    /** @test */
    public function it_can_check_if_has_cert_errors()
    {
        array_map(function($errorNo) {
            $this->assertFalse(SslChecker::hasCertErrors($errorNo));
        }, [
            35, // CURLE_SSL_CONNECT_ERROR
            53, // CURLE_SSL_ENGINE_NOTFOUND
            54, // CURLE_SSL_ENGINE_SETFAILED
            58, // CURLE_SSL_CERTPROBLEM
            59, // CURLE_SSL_CIPHER
        ]);

        array_map(function($errorNo) {
            $this->assertTrue(SslChecker::hasCertErrors($errorNo));
        }, [
            60, // CURLE_SSL_CACERT
            51, // CURLE_SSL_PEER_CERTIFICATE
            77, // CURLE_SSL_CACERT_BADFILE
        ]);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiConnectionException
     */
    public function it_must_throw_api_exception_on_empty_result()
    {
        $method = self::getSslCheckerMethod('checkResult');

        $method->invoke(new SslChecker(), false, 0, 'Unknown');
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiConnectionException
     */
    public function it_must_throw_api_exception_on_error_number()
    {
        $method = self::getSslCheckerMethod('checkResult');

        $method->invoke($this->sslChecker, '{result:success}', 1, 'Unknown');
    }

    /** @test  */
    public function it_must_show_warning_if_stream_extension_not_available()
    {
        $method = self::getSslCheckerMethod('showStreamExtensionWarning');

        $this->assertTrue($method->invoke($this->sslChecker));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the SslChecker reflected method.
     *
     * @param  string  $method
     *
     * @return \ReflectionMethod
     */
    private function getSslCheckerMethod($method)
    {
        return parent::getMethod('Arcanedev\\Stripe\\Http\\Curl\\SslChecker', $method);
    }

    /**
     * Get a black listed Cert by Stripe
     *
     * @return string
     */
    private function getBlackListedCert()
    {
        // {{{ Revoked certificate from api.stripe.com }}}
        $path = realpath(__DIR__ . '/../../data/bad-ca-cert.crt');

        return file_get_contents($path);
    }
}
