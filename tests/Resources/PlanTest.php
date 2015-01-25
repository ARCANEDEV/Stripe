<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Plan;
use Arcanedev\Stripe\Tests\StripeTestCase;

class PlanTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const PLAN_CLASS = 'Arcanedev\\Stripe\\Resources\\Plan';

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
        parent::tearDown();

        unset($this->plan);
        unset($this->planId);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::PLAN_CLASS, $this->plan);
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
