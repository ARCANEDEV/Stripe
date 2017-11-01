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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Stripe\Resources\Plan */
    private $plan;

    /** @var string */
    private $planId = '';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Plan::class, $this->plan);
    }

    /** @test */
    public function it_can_list_all()
    {
        $plans = Plan::all();

        $this->assertTrue($plans->isList());
        $this->assertSame('/v1/plans', $plans->url);
    }

    /** @test */
    public function it_can_update()
    {
        $this->plan = Plan::update($this->planId, [
            'name' => 'A new plan name',
        ]);
        $stripePlan = Plan::retrieve($this->planId);

        $this->assertSame('A new plan name', $this->plan->name);
        $this->assertSame($this->plan->name, $stripePlan->name);
    }

    /** @test */
    public function it_can_save()
    {
        $this->plan->name = 'A new plan name';
        $this->plan->save();

        $stripePlan = Plan::retrieve($this->planId);

        $this->assertSame('A new plan name', $this->plan->name);
        $this->assertSame($this->plan->name, $stripePlan->name);
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
