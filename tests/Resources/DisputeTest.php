<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Dispute;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class DisputeTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class DisputeTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_generate_urls()
    {
        $this->assertEquals('/v1/disputes', Dispute::classUrl());

        $dispute = new Dispute('dp_123');

        $this->assertEquals('/v1/disputes/dp_123', $dispute->instanceUrl());
    }

    /** @test */
    public function it_can_get_all()
    {
        $disputes = Dispute::all([
            'limit' => 3,
        ]);

        $this->assertEquals(3, count($disputes->data));
    }

    /** @test */
    public function it_can_update()
    {
        $name    = 'Bob';
        $charge  = $this->createDisputedCharge();
        $dispute = $charge->dispute;
        $dispute->evidence['customer_name'] = $name;

        $saved = $dispute->save();

        $this->assertEquals($dispute->id, $saved->id);
        $this->assertEquals($name, $saved->evidence['customer_name']);
    }

    /** @test */
    public function it_can_close()
    {
        $charge  = $this->createDisputedCharge();
        $dispute = $charge->dispute->close();

        $this->assertEquals('lost', $dispute->status);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $charge  = $this->createDisputedCharge();
        $dispute = Dispute::retrieve($charge->dispute->id);

        $this->assertEquals($charge->dispute->id, $dispute->id);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a disputed charge
     *
     * @throws \Exception
     *
     * @return Charge|array
     */
    private function createDisputedCharge()
    {
        $charge = Charge::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => [
                'number'    => '4000000000000259',
                'exp_month' => 5,
                'exp_year'  => date('Y') + 1
            ]
        ]);

        $charge   = Charge::retrieve($charge->id);
        $attempts = 0;

        while ($charge->dispute === null) {
            if ($attempts > 5) {
                throw new \Exception('Charge is taking too long to be disputed');
            }

            sleep(1);

            $charge = Charge::retrieve($charge->id);
            $attempts += 1;
        }

        return $charge;
    }
}
