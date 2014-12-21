<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Tests\StripeTest;

use Arcanedev\Stripe\Resources\Balance;
use Arcanedev\Stripe\Utilities\Util;

class BalanceTest extends StripeTest
{
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
    public function testCanBeInstantiate()
    {
        $this->assertStripeInstance('Resources\\Balance', $this->object);
    }

    public function testRetrieve()
    {
        $this->assertEquals("balance", $this->object->object);
        $this->assertTrue(Util::isList($this->object->available));
        $this->assertTrue(Util::isList($this->object->pending));
    }
}
