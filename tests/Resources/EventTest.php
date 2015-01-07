<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Event;

use Arcanedev\Stripe\Tests\StripeTestCase;

class EventTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Event */
    protected $object;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\Event';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->object = new Event;
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
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->object);
    }

    /**
     * @test
     */
    public function testCanGetAll()
    {
        $events = Event::all();

        $this->assertTrue($events->isList());
        $this->assertEquals('/v1/events', $events->url);
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $events = Event::all();

        if (count($events->data) == 0) {
            return ;
        }

        $eventId = $events->data[0]->id;

        $this->object = Event::retrieve($eventId);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->object);
        $this->assertEquals($eventId, $this->object->id);
    }
}
