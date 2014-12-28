<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;

use Arcanedev\Stripe\Tests\StripeTestCase;

class ChargeTest extends StripeTestCase
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
    public function testCanGetUrl()
    {
        $this->assertEquals(Charge::classUrl(), '/v1/charges');

        $charge = new Charge('abcd/efgh');

        $this->assertEquals($charge->instanceUrl(), '/v1/charges/abcd%2Fefgh');
    }

    /**
     * @test
     */
    public function testCanCreate()
    {
        $charge = Charge::create($this->chargeData);

        $this->assertTrue($charge->paid);
        $this->assertFalse($charge->refunded);
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

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $c = Charge::create($this->chargeData);

        $d = Charge::retrieve($c->id);
        $this->assertEquals($d->id, $c->id);
    }

    /**
     * @test
     */
    public function testCanListAll()
    {
        $charges = Charge::all();

        $this->assertEquals('list', $charges->object);
    }

    /**
     * @test
     */
    public function testCanRefundTotalAmount()
    {
        $charge = Charge::create($this->chargeData);

        $charge->refund();
        $this->assertTrue($charge->refunded);
        $this->assertEquals(1, count($charge->refunds->data));

        $refund = $charge->refunds->data[0];
        $this->assertEquals('refund', $refund->object);
        $this->assertEquals(100, $refund->amount);
    }

    /**
     * @test
     */
    public function testCanRefundPartialAmount()
    {
        $charge = Charge::create($this->chargeData);

        $charge->refund(['amount' => 50,]);
        $this->assertFalse($charge->refunded);
        $this->assertEquals(1, count($charge->refunds->data));

        $charge->refund(['amount' => 50,]);
        $this->assertTrue($charge->refunded);
        $this->assertEquals(2, count($charge->refunds->data));
    }

    // TODO: Add tests for updateDispute() & closeDispute() methods

    /* ------------------------------------------------------------------------------------------------
     |  Test Metadata Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanUpdateOneMetadata()
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
    public function testCanUpdateAllMetadata()
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
    public function testCanMarkAsFraudulent()
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
    public function testCanMarkAsSafe()
    {
        $charge = Charge::create($this->chargeData);

        $charge->markAsSafe();

        $updatedCharge = Charge::retrieve($charge->id);
        $this->assertEquals('safe', $updatedCharge['fraud_details']['user_report']);
    }
}
