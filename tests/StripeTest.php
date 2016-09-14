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

        $this->assertSame($this->myApiKey, Stripe::getApiKey());
    }

    /** @test */
    public function it_can_get_and_set_api_key()
    {
        $this->assertSame(self::API_KEY, Stripe::getApiKey());

        Stripe::setApiKey($this->myApiKey);

        $this->assertSame($this->myApiKey, Stripe::getApiKey());
    }

    /** @test */
    public function it_can_get_and_set_account_id()
    {
        $accountId = 'stripe_account_id';

        $this->assertFalse(Stripe::hasAccountId());
        $this->assertNull(Stripe::getAccountId());

        Stripe::setAccountId($accountId);

        $this->assertTrue(Stripe::hasAccountId());
        $this->assertSame($accountId, Stripe::getAccountId());
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
        $this->assertSame($url, Stripe::getApiBaseUrl());

        Stripe::setApiBaseUrl($baseUrl);
        $this->assertSame($baseUrl, Stripe::getApiBaseUrl());
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

        $this->assertSame($url, Stripe::getUploadBaseUrl());

        $url .= '/v2';
        Stripe::setUploadBaseUrl($url);

        $this->assertSame($url, Stripe::getUploadBaseUrl());
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

        $this->assertSame($this->myApiVersion, Stripe::getApiVersion());
        $this->assertSame($this->myApiVersion, Stripe::version());

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

    /** @test */
    public function it_can_set_and_get_app_info()
    {
        $appInfo = Stripe::getAppInfo();

        $this->assertInternalType('array', $appInfo);

        Stripe::setAppInfo($name = 'ARCANEDEV', $version = '1.0.0', $url = 'http://www.arcanedev.net');

        $appInfo = Stripe::getAppInfo();

        $this->assertSame($name,    $appInfo['name']);
        $this->assertSame($version, $appInfo['version']);
        $this->assertSame($url,     $appInfo['url']);
    }
}
