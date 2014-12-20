<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Balance;
use Arcanedev\Stripe\Util;

use Arcanedev\Stripe\Tests\StripeTest;

class BalanceTest extends StripeTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Balance */
    private $balance;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->balance = Balance::retrieve();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function testRetrieve()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Balance', $this->balance);
        $this->assertEquals("balance", $this->balance->object);
        $this->assertTrue(Util::isList($this->balance->available));
        $this->assertTrue(Util::isList($this->balance->pending));
    }
}
