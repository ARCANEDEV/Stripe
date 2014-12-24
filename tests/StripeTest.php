<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Stripe;

class StripeTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanInitStrip()
    {
        $apiKey = 'my-secret-api-key';
        Stripe::init($apiKey);

        $this->assertEquals($apiKey, Stripe::getApiKey());
    }

    /**
     * @test
     */
    public function testCanGetAndSetApiKey()
    {
        $this->assertEquals(self::API_KEY, Stripe::getApiKey());

        $apiKey = 'my-secret-api-key';

        Stripe::setApiKey($apiKey);
        $this->assertEquals($apiKey, Stripe::getApiKey());
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenApiIsNotString()
    {
        Stripe::setApiKey(null);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenApiIsEmptyString()
    {
        Stripe::setApiKey('  ');
    }

    /**
     * @test
     */
    public function testCanGetAndSetApiBaseUrl()
    {
        $baseUrl = 'https://api.stripe.com';
        $url     = $baseUrl . '/v2';

        Stripe::setApiBaseUrl($url);
        $this->assertEquals($url, Stripe::getApiBaseUrl());

        Stripe::setApiBaseUrl($baseUrl);
        $this->assertEquals($baseUrl, Stripe::getApiBaseUrl());
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenApiBaseUrlIsNotString()
    {
        Stripe::setApiBaseUrl(null);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenApiBaseUrlIsNotValidUrl()
    {
        Stripe::setApiBaseUrl('localhost.com');
    }

    /**
     * @test
     */
    public function testCanGetAndSetUploadBaseUrl()
    {
        $url = 'https://uploads.stripe.com';
        $this->assertEquals($url, Stripe::getUploadBaseUrl());

        $url .= '/v2';
        Stripe::setUploadBaseUrl($url);
        $this->assertEquals($url, Stripe::getUploadBaseUrl());
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenUploadBaseUrlIsNotString()
    {
        Stripe::setUploadBaseUrl(null);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenUploadBaseUrlIsNotValidUrl()
    {
        Stripe::setUploadBaseUrl('storage.web.com');
    }

    /**
     * @test
     */
    public function testCanGetAndSetApiVersion()
    {
        $this->assertFalse(Stripe::hasApiVersion());
        $this->assertNull(Stripe::getApiVersion());

        $version = '2.0.0';
        Stripe::setApiVersion($version);

        $this->assertEquals($version, Stripe::getApiVersion());
        $this->assertEquals($version, Stripe::version());

        Stripe::setApiVersion(null);
        $this->assertNull(Stripe::version());
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenApiVersionIsNotNullOrString()
    {
        Stripe::setApiVersion(true);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function testMustThrowApiExceptionWhenApiVersionIsValid()
    {
        Stripe::setApiVersion('alpha.version.1');
    }

    /**
     * @test
     */
    public function testCanSetAndGetVerifySslCerts()
    {
        $this->assertTrue(Stripe::getVerifySslCerts());

        Stripe::setVerifySslCerts(false);
        $this->assertFalse(Stripe::getVerifySslCerts());

        Stripe::setVerifySslCerts('on');
        $this->assertTrue(Stripe::getVerifySslCerts());
    }
}
