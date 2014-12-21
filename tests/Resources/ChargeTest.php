<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;

use Arcanedev\Stripe\Tests\StripeTest;

class ChargeTest extends StripeTest
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    private $chargeData = [];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->chargeData = [
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => [
                'number'    => '4242424242424242',
                'exp_month' => 5,
                'exp_year'  => 2015
            ],
        ];
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->chargeData = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testChargeUrls()
    {
        $this->assertEquals(Charge::classUrl(), '/v1/charges');

        $charge = new Charge('abcd/efgh');

        $this->assertEquals($charge->instanceUrl(), '/v1/charges/abcd%2Fefgh');
    }

    /**
     * @test
     */
    public function testCanCreateCharge()
    {
        $c = Charge::create($this->chargeData);

        $this->assertTrue($c->paid);
        $this->assertFalse($c->refunded);
    }

    /**
     * @test
     */
    public function testCanRetrieveCharge()
    {
        $c = Charge::create($this->chargeData);

        $d = Charge::retrieve($c->id);
        $this->assertEquals($d->id, $c->id);
    }

    /**
     * @test
     */
    public function testCanUpdateChargeOneMetadata()
    {
        $charge = Charge::create($this->chargeData);

        $charge->metadata['test'] = 'foo bar';
        $charge->save();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertEquals('foo bar', $updatedCharge->metadata['test']);
    }

    /**
     * @test
     */
    public function testCanUpdateChargeAllMetadata()
    {
        $charge = Charge::create($this->chargeData);

        $charge->metadata = ['test' => 'foo bar'];
        $charge->save();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertEquals('foo bar', $updatedCharge->metadata['test']);
    }

    /**
     * @test
     */
    public function testCanMarkChargeAsFraudulent()
    {
        $charge = Charge::create($this->chargeData);

        $charge->refunds->create();
        $charge->markAsFraudulent();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertEquals('fraudulent', $updatedCharge['fraud_details']['user_report']);
    }

    /**
     * @test
     */
    public function testCanMarkChargeAsSafe()
    {
        $charge = Charge::create($this->chargeData);

        $charge->markAsSafe();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertEquals('safe', $updatedCharge['fraud_details']['user_report']);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\CardException
     * @expectedExceptionCode 402
     */
    public function testMustThrowCardErrorOnDeclinedCard()
    {
        $declinedCard = [
            'number'    => '4000000000000002',
            'exp_month' => '3',
            'exp_year'  => '2020'
        ];

        Charge::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => $declinedCard
        ]);
    }
}
