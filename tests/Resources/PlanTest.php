<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Plan;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     PlanTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PlanTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Plan */
    private $plan;

    /** @var string */
    private $planId = '';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->plan   = self::retrieveOrCreatePlan();
        $this->planId = $this->plan->id;
    }

    public function tearDown()
    {
        unset($this->plan);
        unset($this->planId);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Plan', $this->plan);
    }

    /** @test */
    public function it_can_list_all()
    {
        $plans = Plan::all();

        $this->assertTrue($plans->isList());
        $this->assertEquals('/v1/plans', $plans->url);
    }

    /** @test */
    public function it_can_save()
    {
        $this->plan->name = 'A new plan name';
        $this->plan->save();
        $this->assertEquals($this->plan->name, 'A new plan name');

        $stripePlan = Plan::retrieve($this->planId);
        $this->assertEquals($this->plan->name, $stripePlan->name);
    }

    /** @test */
    public function it_can_delete()
    {
        $this->plan->delete();

        $this->assertTrue($this->plan->deleted);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode    404
     * @expectedExceptionMessage No such plan: 0
     */
    public function it_must_throw_invalid_request_exception_on_false_id()
    {
        Plan::retrieve('0');
    }
}
