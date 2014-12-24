<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Utilities\Util;

class UtilTest extends StripeTestCase
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
    public function testIsList()
    {
        $this->assertTrue(Util::isList([
            5, 'nstaoush', []
        ]));

        $this->assertFalse(Util::isList([
            5, 'nstaoush', [], 'bar' => 'baz'
        ]));
    }

    /**
     * @test
     */
    public function testThatPHPHasValueSemanticsForArrays()
    {
        $original   = ['php-arrays' => 'value-semantics'];
        $derived    = $original;
        $derived['php-arrays'] = 'reference-semantics';

        $this->assertEquals('value-semantics', $original['php-arrays']);
    }

    /**
     * @test
     */
    public function testConvertStripeObjectToArrayIncludesId()
    {
        $customer = self::createTestCustomer();
        $this->assertTrue(array_key_exists("id", $customer->__toArray(true)));
    }
}
