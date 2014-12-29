<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\ListObject;

class ListObjectTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\ListObject';
    /** @var ListObject */
    private $listObject;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->listObject = new ListObject;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->listObject);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->listObject);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function testMustThrowApiExceptionWhenUrlIsEmpty()
    {
        $this->listObject->all();
    }
}
