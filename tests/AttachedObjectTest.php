<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\AttachedObject;

/**
 * Class     AttachedObjectTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AttachedObjectTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_count_elements()
    {
        $ao = new AttachedObject;

        $this->assertCount(0, $ao);
        $this->assertSame(0, $ao->count());

        $ao['key1'] = 'value1';

        $this->assertCount(1, $ao);
        $this->assertSame(1, $ao->count());

        $ao['key2'] = 'value2';

        $this->assertCount(2, $ao);
        $this->assertSame(2, $ao->count());
    }
}
