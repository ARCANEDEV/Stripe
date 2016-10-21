<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\UtilSet;

/**
 * Class     UtilSetTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UtilSetTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Utilities\UtilSet */
    private $utilSet;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->utilSet = new UtilSet;
    }

    public function tearDown()
    {
        unset($this->utilSet);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(UtilSet::class, $this->utilSet);
    }

    /** @test */
    public function it_can_get_count()
    {
        $this->assertSame(0,  $this->utilSet->count());
        $this->assertSame(0,  count($this->utilSet));
        $this->assertCount(0, $this->utilSet);

        $this->utilSet->add('hello');

        $this->assertSame(1,  $this->utilSet->count());
        $this->assertSame(1,  count($this->utilSet));
        $this->assertCount(1, $this->utilSet);
    }
}
