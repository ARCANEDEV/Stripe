<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\UtilSet;

class UtilSetTest extends StripeTestCase {
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
    /**
     * @test
     */
    public function testCanCount()
    {
        $this->assertEquals(0, $this->utilSet->count());

        $this->utilSet->add('hello');

        $this->assertEquals(1, $this->utilSet->count());
    }
}
