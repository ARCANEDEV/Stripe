<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Plan;

use Arcanedev\Stripe\Tests\StripeTestCase;

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
        parent::tearDown();

        unset($this->plan);
        unset($this->planId);
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
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\Plan',
            $this->plan
        );
    }

    /**
     * @test
     */
    public function testCanGetAll()
    {
        $plans = Plan::all();

        $this->assertTrue($plans->isList());
        $this->assertEquals('/v1/plans', $plans->url);
    }

    /**
     * @test
     */
    public function testCanSave()
    {
        $this->plan->name = 'A new plan name';
        $this->plan->save();
        $this->assertEquals($this->plan->name, 'A new plan name');

        $stripePlan = Plan::retrieve($this->planId);
        $this->assertEquals($this->plan->name, $stripePlan->name);
    }

    /**
     * @test
     */
    public function testCanDelete()
    {
        $this->plan->delete();

        $this->assertTrue($this->plan->deleted);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage No such plan: 0
     */
    public function testMustThrowInvalidRequestErrorOnFalseId()
    {
        Plan::retrieve('0');
    }
}
