<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Balance;
use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Class BalanceTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class BalanceTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const BALANCE_CLASS = 'Arcanedev\\Stripe\\Resources\\Balance';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Balance */
    protected $object;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->object = Balance::retrieve();
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
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::BALANCE_CLASS, $this->object);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $this->assertEquals("balance", $this->object->object);
        $this->assertTrue(Util::isList($this->object->available));
        $this->assertTrue(Util::isList($this->object->pending));
    }
}
