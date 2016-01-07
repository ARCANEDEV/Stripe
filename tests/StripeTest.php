<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Stripe;

/**
 * Class     StripeTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class StripeTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_init_stripe()
    {
        Stripe::init($this->myApiKey);

        $this->assertEquals($this->myApiKey, Stripe::getApiKey());
    }

    /** @test */
    public function it_can_get_and_set_api_key()
    {
        $this->assertEquals(self::API_KEY, Stripe::getApiKey());

        Stripe::setApiKey($this->myApiKey);
        $this->assertEquals($this->myApiKey, Stripe::getApiKey());
    }

    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_api_is_not_string()
    {
        Stripe::setApiKey(null);
    }

    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiKeyNotSetException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_api_is_empty_string()
    {
        Stripe::setApiKey('  ');
    }

    /** @test */
    public function it_can_get_and_set_api_base_url()
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
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_api_base_url_is_not_string()
    {
        Stripe::setApiBaseUrl(null);
    }

    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_api_base_url_is_not_valid_url()
    {
        Stripe::setApiBaseUrl('localhost.com');
    }

    /** @test */
    public function it_can_get_and_set_upload_base_url()
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
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_upload_base_url_is_not_string()
    {
        Stripe::setUploadBaseUrl(null);
    }

    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_upload_base_url_is_not_valid_url()
    {
        Stripe::setUploadBaseUrl('storage.web.com');
    }

    /** @test */
    public function it_can_get_and_set_api_version()
    {
        $this->assertFalse(Stripe::hasApiVersion());
        $this->assertNull(Stripe::getApiVersion());

        Stripe::setApiVersion($this->myApiVersion);

        $this->assertEquals($this->myApiVersion, Stripe::getApiVersion());
        $this->assertEquals($this->myApiVersion, Stripe::version());

        Stripe::setApiVersion(null);
        $this->assertNull(Stripe::version());
    }

    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_api_version_is_not_null_or_string()
    {
        Stripe::setApiVersion(true);
    }

    /**
     * @test
     *
     * @expectedException     \Arcanedev\Stripe\Exceptions\ApiException
     * @expectedExceptionCode 500
     */
    public function it_must_throw_api_exception_when_api_version_is_valid()
    {
        Stripe::setApiVersion('alpha.version.1');
    }

    /** @test */
    public function it_can_set_and_get_verify_ssl_certs()
    {
        $this->assertTrue(Stripe::getVerifySslCerts());

        Stripe::setVerifySslCerts(false);
        $this->assertFalse(Stripe::getVerifySslCerts());

        Stripe::setVerifySslCerts('on');
        $this->assertTrue(Stripe::getVerifySslCerts());
    }
}
