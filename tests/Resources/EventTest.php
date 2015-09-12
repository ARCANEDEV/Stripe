<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Event;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     EventTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class EventTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const EVENT_CLASS = 'Arcanedev\\Stripe\\Resources\\Event';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Event */
    protected $event;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->event = new Event;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->event);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::EVENT_CLASS, $this->event);
    }

    /** @test */
    public function it_can_list_all()
    {
        $events = Event::all();

        $this->assertTrue($events->isList());
        $this->assertEquals('/v1/events', $events->url);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $events = Event::all();

        if (count($events->data) == 0) {
            return ;
        }

        $eventId = $events->data[0]->id;

        $this->event = Event::retrieve($eventId);

        $this->assertInstanceOf(self::EVENT_CLASS, $this->event);
        $this->assertEquals($eventId, $this->event->id);
    }
}
