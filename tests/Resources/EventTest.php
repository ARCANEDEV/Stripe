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
        unset($this->event);

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
            'Arcanedev\\Stripe\\Resources\\Event',
            $this->event
        );
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

        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\Event',
            $this->event
        );
        $this->assertEquals($eventId, $this->event->id);
    }
}
