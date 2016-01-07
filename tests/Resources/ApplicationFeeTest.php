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
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var ApplicationFee */
    protected $object;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\ApplicationFee', $this->object
        );
    }

    /** @test */
    public function it_can_get_class_name()
    {
        $this->assertEquals('application_fee', $this->object->className());
    }

    /** @test */
    public function it_can_get_url()
    {
        $this->assertEquals(
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

        $this->assertEquals('/v1/application_fees', $fees->url);
    }
}
