<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\ApplicationFee;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     ApplicationFeeTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ApplicationFeeTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\Stripe\Resources\ApplicationFee */
    protected $object;

    /* -----------------------------------------------------------------
     |  Main Functions
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->object = new ApplicationFee('abcd/efgh');
    }

    public function tearDown()
    {
        unset($this->object);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ApplicationFee::class, $this->object);
    }

    /** @test */
    public function it_can_get_class_name()
    {
        $this->assertSame('application_fee', $this->object->className());
    }

    /** @test */
    public function it_can_get_url()
    {
        $this->assertSame(
            '/v1/application_fees/abcd%2Fefgh',
            $this->object->instanceUrl()
        );
    }

    /**
     * @test
     *
     * @expectedException  \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    public function it_must_throw_invalid_request_error_exception_on_invalid_id()
    {
        (new ApplicationFee)->instanceUrl();
    }

    /** @test */
    public function it_can_list_all()
    {
        $fees = ApplicationFee::all();

        $this->assertSame('/v1/application_fees', $fees->url);
    }

//    /** @test */
//    public function it_can_retrieve()
//    {
//        // TODO: Complete the retrieve test
//    }

//    /** @test */
//    public function it_can_update()
//    {
//        // TODO: Complete the update test
//    }

//    /** @test */
//    public function it_can_refund()
//    {
//        // TODO: Complete the refund test
//    }

    /** @test */
    public function it_can_create_refund()
    {
        $this->mockRequest(
            'POST',
            '/v1/application_fees/fee_123/refunds',
            array(),
            array('id' => 'fr_123', 'object' => 'fee_refund')
        );

        $feeRefund = ApplicationFee::createRefund(
            'fee_123'
        );

        $this->assertSame('fr_123', $feeRefund->id);
        $this->assertSame('fee_refund', $feeRefund->object);
    }

    /** @test */
    public function it_can_retrieve_refund()
    {
        $this->mockRequest(
            'GET',
            '/v1/application_fees/fee_123/refunds/fr_123',
            [],
            ['id' => 'fr_123', 'object' => 'fee_refund']
        );

        $feeRefund = ApplicationFee::retrieveRefund('fee_123', 'fr_123');

        $this->assertSame('fr_123', $feeRefund->id);
        $this->assertSame('fee_refund', $feeRefund->object);
    }

    /** @test */
    public function it_can_update_refund()
    {
        $this->mockRequest(
            'POST',
            '/v1/application_fees/fee_123/refunds/fr_123',
            ['metadata' => ['foo' => 'bar']],
            ['id' => 'fr_123', 'object' => 'fee_refund']
        );

        $feeRefund = ApplicationFee::updateRefund('fee_123', 'fr_123', [
            'metadata' => ['foo' => 'bar']
        ]);

        $this->assertSame('fr_123', $feeRefund->id);
        $this->assertSame('fee_refund', $feeRefund->object);
    }

    /** @test */
    public function it_can_list_all_refunds()
    {
        $this->mockRequest(
            'GET',
            '/v1/application_fees/fee_123/refunds',
            [],
            ['object' => 'list', 'data' => []]
        );

        $feeRefunds = ApplicationFee::allRefunds('fee_123');

        $this->assertSame('list', $feeRefunds->object);
        $this->assertEmpty($feeRefunds->data);
    }
}
