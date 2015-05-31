<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Tests\StripeTestCase;

class TransferReversalTest extends StripeTestCase
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
    public function testList()
    {
        $transfer = self::createTestTransfer();
        $all      = $transfer->reversals->all();
        $this->assertSame(false, $all['has_more']);
        $this->assertSame(0, count($all->data));
    }
}
