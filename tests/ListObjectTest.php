<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Collection;

/**
 * Class     ListObjectTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ListObjectTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Collection';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Collection */
    private $collection;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->collection = new Collection;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->collection);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->collection);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_when_url_is_empty()
    {
        $this->collection->all();
    }
}
