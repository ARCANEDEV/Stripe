<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\ApplicationFeeRefund;

use Arcanedev\Stripe\Tests\StripeTestCase;

class ApplicationFeeRefundTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var ApplicationFeeRefund */
    private $appFeeRefund;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->appFeeRefund = new ApplicationFeeRefund;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->appFeeRefund);
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
            $this->appFeeRefund
        );
    }

    /**
     * @test
     */
    public function testCanGetInstanceUrl()
    {
        $refundId = 'refund_id';
        $feeId    = 'fee_id';

        $this->appFeeRefund->id  = $refundId;
        $this->appFeeRefund->fee = $feeId;

        $this->assertEquals(
            "/v1/application_fees/$feeId/refunds/$refundId",
            $this->appFeeRefund->instanceUrl()
        );
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    public function testMustThrowInvalidRequestExceptionOnIdEmpty()
    {
        $this->appFeeRefund->instanceUrl();
    }

    /**
     * @test
     */
    public function testCanSave()
    {
        // $fee = ApplicationFee::retrieve("FeeID");
        // $re  = $fee->refunds->retrieve("RefundID");
        // $re->metadata["key"] = "value";
        // $re->save();
    }
}
