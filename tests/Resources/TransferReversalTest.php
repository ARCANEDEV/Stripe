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
    public function testList()
    {
        $transfer = self::createTestTransfer();
        $all      = $transfer->reversals->all();
        $this->assertSame(false, $all['has_more']);
        $this->assertSame(0, count($all->data));
    }
}
