<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Object;

class ObjectTest extends StripeTestCase
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
    public function testArrayAccessorsSemantics()
    {
        $s          = new Object;
        $s['foo']   = 'a';
        $this->assertEquals('a', $s['foo']);
        $this->assertTrue(isset($s['foo']));
        unset($s['foo']);
        $this->assertFalse(isset($s['foo']));
    }

    /**
     * @test
     */
    public function testNormalAccessorsSemantics()
    {
        $s      = new Object;
        $s->foo = 'a';
        $this->assertEquals('a', $s->foo);
        $this->assertTrue(isset($s->foo));
        unset($s->foo);
        $this->assertFalse(isset($s->foo));
    }

    /**
     * @test
     */
    public function testArrayAccessorsMatchNormalAccessors()
    {
        $s        = new Object;
        $s->foo   = 'a';
        $this->assertEquals('a', $s['foo']);

        $s['bar'] = 'b';
        $this->assertEquals('b', $s->bar);
    }

    /**
     * @test
     */
    public function testKeys()
    {
        $s      = new Object;
        $s->foo = 'a';
        $this->assertEquals(['foo'], $s->keys());
    }
}
