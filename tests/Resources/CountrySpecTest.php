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

        $this->assertSame($country->object, 'country_spec');
        $this->assertInstanceOf(CountrySpec::class, $country);

        $this->assertSame($country->id, $code);
        $this->assertCountGreaterThan(0, $country->supported_bank_account_currencies);
        $this->assertCountGreaterThan(0, $country->supported_payment_currencies);
        $this->assertCountGreaterThan(0, $country->supported_payment_methods);
        $this->assertCountGreaterThan(0, $country->verification_fields);
    }

    /** @test */
    public function it_can_list()
    {
        $countries = CountrySpec::all();

        $this->assertSame('list', $countries->object);
        $this->assertCountGreaterThan(0, count($countries->data));

        $country   = $countries->data[0];

        $this->assertSame('country_spec', $country->object);
        $this->assertInstanceOf(CountrySpec::class, $country);
    }
}
