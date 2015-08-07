<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Utilities\Util;

/**
 * Class UtilTest
 * @package Arcanedev\Stripe\Tests
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

        $this->assertEquals('value-semantics', $original['php-arrays']);
    }

    /** @test */
    public function it_convert_stripe_object_to_array_that_includes_id()
    {
        $customer = self::createTestCustomer();
        $this->assertArrayHasKey('id', $customer->toArray(true));
    }
}
