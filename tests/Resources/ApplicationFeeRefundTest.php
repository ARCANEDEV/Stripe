<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\ApplicationFeeRefund;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ApplicationFeeRefundTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
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
        unset($this->appFeeRefund);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\ApplicationFeeRefund',
            $this->appFeeRefund
        );
    }

    /** @test */
    public function it_can_get_instance_url()
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
    public function it_must_throw_invalid_request_exception_on_empty_id()
    {
        $this->appFeeRefund->instanceUrl();
    }

    /** @test */
    public function it_can_save()
    {
        // $fee = ApplicationFee::retrieve("FeeID");
        // $this->appFeeRefund = $fee->refunds->retrieve("RefundID");
        // $this->appFeeRefund->metadata["key"] = "value";
        // $this->appFeeRefund->save();
    }
}
