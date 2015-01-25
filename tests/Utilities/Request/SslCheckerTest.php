<?php namespace Arcanedev\Stripe\Tests\Utilities\Request;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Request\SslChecker;

class SslCheckerTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const SSL_CHECKER_CLASS = 'Arcanedev\\Stripe\\Utilities\\Request\\SslChecker';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Utilities\Request\SslChecker */
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
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::SSL_CHECKER_CLASS, $this->sslChecker);
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
        $certPath = $this->sslChecker->caBundle();

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
        // {{{ Revoked certificate from api.stripe.com }}}
        $path = realpath(__DIR__ . '/../../data/bad-ca-cert.crt');

        return file_get_contents($path);
    }
}
