<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Source;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     SourceTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SourceTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_create_and_retrieve()
    {
        Stripe::setApiKey('sk_test_JieJALRz7rPz7boV17oMma7a');

        $s = Source::create([
            'type'         => 'bitcoin',
            'currency'     => 'usd',
            'amount'       => '100',
            'owner[email]' => 'gdb@stripe.com',
        ]);

        $this->assertSame('bitcoin', $s->type);

        $source = Source::retrieve($s->id);

        $this->assertSame(100, $source->amount);
    }
}
