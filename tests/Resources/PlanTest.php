<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Plan;
use Arcanedev\Stripe\Tests\StripeTest;

class PlanTest extends StripeTest
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

        $this->planId   = 'gold-' . parent::randomString();

        $this->plan     = Plan::create([
            'amount'   => 2000,
            'interval' => 'month',
            'currency' => 'usd',
            'name'     => 'Plan',
            'id'       => $this->planId
        ]);
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
}
