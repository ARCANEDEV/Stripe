<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\CountrySpec;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     CountrySpecTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CountrySpecTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_retrieve()
    {
        $code    = 'US';
        $country = CountrySpec::retrieve($code);

        $this->assertEquals($country->object, 'country_spec');
        $this->assertEquals($country->id, $code);
        $this->assertGreaterThan(0, count($country->supported_bank_account_currencies));
        $this->assertGreaterThan(0, count($country->supported_payment_currencies));
        $this->assertGreaterThan(0, count($country->supported_payment_methods));
        $this->assertGreaterThan(0, count($country->verification_fields));
    }

    /** @test */
    public function it_can_list()
    {
        $countries = CountrySpec::all();

        $this->assertEquals('list', $countries->object);
        $this->assertGreaterThan(0, count($countries->data));

        $country   = $countries->data[0];

        $this->assertEquals('country_spec', $country->object);
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\CountrySpec', $country);
    }
}
