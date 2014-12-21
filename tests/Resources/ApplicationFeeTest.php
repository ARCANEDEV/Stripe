<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\ApplicationFee;
use Arcanedev\Stripe\Tests\StripeTest;

class ApplicationFeeTest extends StripeTest
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
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiable()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\ApplicationFee',
            $this->object
        );
    }

    /**
     * @test
     */
    public function testCanGetClassName()
    {
        $this->assertEquals('application_fee', $this->object->className());
    }

    /**
     * @test
     */
    public function testUrls()
    {
        $this->assertEquals(
            '/v1/application_fees/abcd%2Fefgh',
            $this->object->instanceUrl()
        );
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    public function testMustThrowInvalidRequestErrorExceptionOnInvalidId()
    {
        (new ApplicationFee)->instanceUrl();
    }

    /**
     * @test
     */
    public function testList()
    {
        $fees = ApplicationFee::all();

        $this->assertEquals('/v1/application_fees', $fees->url);
    }
}
