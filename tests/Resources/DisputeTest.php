<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Dispute;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     DisputeTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DisputeTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_generate_urls()
    {
        $this->assertSame('/v1/disputes', Dispute::classUrl());

        $dispute = new Dispute('dp_123');

        $this->assertSame('/v1/disputes/dp_123', $dispute->instanceUrl());
    }

    /** @test */
    public function it_can_get_all()
    {
        $disputes = Dispute::all(['limit' => 3]);

        $this->assertSame(3, count($disputes->data));
    }

    /** @test */
    public function it_can_update()
    {
        $name    = 'Bob';
        $charge  = $this->createDisputedCharge();
        $updated = Dispute::update($charge->dispute, [
            'evidence' => [
                'customer_name' => $name,
            ],
        ]);

        $this->assertSame($charge->dispute, $updated->id);
        $this->assertSame($name,            $updated->evidence['customer_name']);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $charge  = $this->createDisputedCharge();
        $dispute = Dispute::retrieve($charge->dispute);

        $this->assertSame($charge->dispute, $dispute->id);
    }

    /** @test */
    public function it_can_save()
    {
        $name    = 'Bob';
        $charge  = $this->createDisputedCharge();
        $dispute = Dispute::retrieve($charge->dispute);
        $dispute->evidence['customer_name'] = $name;

        $saved = $dispute->save();

        $this->assertSame($dispute->id, $saved->id);
        $this->assertSame($name, $saved->evidence['customer_name']);
    }

    /** @test */
    public function it_can_close()
    {
        $charge  = $this->createDisputedCharge();
        $dispute = Dispute::retrieve($charge->dispute);

        $this->assertNotSame('lost', $dispute->status);

        $dispute = $dispute->close();

        $this->assertSame('lost', $dispute->status);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
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
            'amount'   => 100,
            'currency' => 'usd',
            'source'   => 'tok_createDispute',
        ]);

        $charge   = Charge::retrieve($charge->id);
        $attempts = 0;

        while ($charge->dispute === null) {
            if ($attempts > 5)
                throw new \Exception('Charge is taking too long to be disputed');

            sleep(1);

            $charge = Charge::retrieve($charge->id);
            $attempts += 1;
        }

        return $charge;
    }
}
