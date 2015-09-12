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
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const UTILSET_CLASS = 'Arcanedev\\Stripe\\Utilities\\UtilSet';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var UtilSet */
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
        parent::tearDown();

        unset($this->utilSet);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::UTILSET_CLASS, $this->utilSet);
    }

    /** @test */
    public function it_can_get_count()
    {
        $this->assertEquals(0, $this->utilSet->count());

        $this->utilSet->add('hello');

        $this->assertEquals(1, $this->utilSet->count());
    }
}
