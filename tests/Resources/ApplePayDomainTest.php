<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\ApplePayDomain;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ApplePayDomainTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApplePayDomainTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_create()
    {
        $this->mockRequest(
            'POST',
            '/v1/apple_pay/domains',
            ['domain_name' => 'test.com'],
            [
                'id'     => 'apwc_create',
                'object' => 'apple_pay_domain'
            ]
        );

        $domain = ApplePayDomain::create(['domain_name' => 'test.com']);

        $this->assertSame('apwc_create', $domain->id);
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\ApplePayDomain', $domain);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $this->mockRequest(
            'GET',
            '/v1/apple_pay/domains/apwc_retrieve',
            [],
            [
                'id' => 'apwc_retrieve',
                'object' => 'apple_pay_domain']
        );

        $domain = ApplePayDomain::retrieve('apwc_retrieve');

        $this->assertSame('apwc_retrieve', $domain->id);
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\ApplePayDomain', $domain);
    }

    /** @test */
    public function testDeletion()
    {
        $domain = ApplePayDomain::create(['domain_name' => 'jackshack.website']);

        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\ApplePayDomain', $domain);

        $this->mockRequest(
            'DELETE',
            '/v1/apple_pay/domains/' . $domain->id,
            [],
            ['deleted' => true]
        );

        $domain->delete();

        $this->assertTrue($domain->deleted);
    }

    /** @test */
    public function it_can_get_all()
    {
        $this->mockRequest(
            'GET',
            '/v1/apple_pay/domains',
            [],
            [
                'url'    => '/v1/apple_pay/domains',
                'object' => 'list'
            ]
        );

        $domains = ApplePayDomain::all();

        $this->assertSame($domains->url, '/v1/apple_pay/domains');
    }
}
