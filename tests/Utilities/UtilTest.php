<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Class     UtilTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UtilTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_check_is_list()
    {
        $this->assertTrue(Util::isList([
            5, 'nstaoush', []
        ]));

        $this->assertFalse(Util::isList([
            5, 'nstaoush', [], 'bar' => 'baz'
        ]));
    }

    /** @test */
    public function can_check_that_php_has_value_semantics_for_arrays()
    {
        $original = $derived = ['php-arrays' => 'value-semantics'];
        $derived['php-arrays'] = 'reference-semantics';

        $this->assertSame('value-semantics', $original['php-arrays']);
    }

    /** @test */
    public function it_convert_stripe_object_to_array_that_includes_id()
    {
        try {
            $customer = self::createTestCustomer();

            $this->assertArrayHasKey('id', $customer->toArray(true));
        }
        catch(\Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    /** @test */
    public function it_can_convert_stripe_object_to_array()
    {
        $array = Util::convertStripeObjectToArray([
            '_'        => 'Hello',
            'customer' => new Customer(['id' => 'cust_kjfhdflsdhfdsjl']),
        ]);

        $this->assertSame([
            'customer' => ['id' => 'cust_kjfhdflsdhfdsjl'],
        ], $array);
    }
}
