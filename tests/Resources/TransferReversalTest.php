<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     TransferReversalTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransferReversalTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_get_all()
    {
        $transfer = self::createTestTransfer();
        $all      = $transfer->reversals->all();

        $this->assertFalse($all['has_more']);
        $this->assertCount(0, $all->data);
    }
}
