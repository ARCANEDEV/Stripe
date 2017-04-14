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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The resource that was traditionally called "transfer" became a "payout" in API version 2017-04-06.
     * We're testing traditional transfers here, so we force the API version just prior anywhere that we need to.
     *
     * @var array
     */
    protected $options = ['stripe_version' => '2017-02-14'];

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_all()
    {
        $transfer = self::createTestTransfer([], $this->options);
        $all      = $transfer->reversals->all();

        $this->assertFalse($all['has_more']);
        $this->assertCount(0, $all->data);
    }
}
