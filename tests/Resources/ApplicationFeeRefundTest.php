<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\ApplicationFeeRefund;

use Arcanedev\Stripe\Tests\StripeTest;

class ApplicationFeeRefundTest extends StripeTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var ApplicationFeeRefund */
    private $refund;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->refund = new ApplicationFeeRefund;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->refund);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\ApplicationFeeRefund',
            $this->refund
        );
    }

    /**
     * @test
     */
    public function testCanGetInstanceUrl()
    {
        $this->refund->id = 'refund_id';
        $this->refund->fee = 'fee_id';

        $this->assertEquals(
            '/v1/application_fees/fee_id/refunds/refund_id',
            $this->refund->instanceUrl()
        );
    }
}
