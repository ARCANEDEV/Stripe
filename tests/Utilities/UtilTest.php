<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Class UtilTest
 * @package Arcanedev\Stripe\Tests\Utilities
 */
class UtilTest extends StripeTestCase
{
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
    /** @test */
    public function it_can_convert_stripe_object_to_array()
    {
        $array = Util::convertStripeObjectToArray([
            '_'         => 'Hello',
            'customer'  => new Customer([
                "id"    => 'cust_kjfhdflsdhfdsjl',
            ])
        ]);

        $this->assertEquals([
            'customer' => [
                "id"    => 'cust_kjfhdflsdhfdsjl',
            ]
        ], $array);
    }
}
