<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\ListObject;

class ListObjectTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\ListObject';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
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
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->listObject);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_when_url_is_empty()
    {
        $this->listObject->all();
    }
}
