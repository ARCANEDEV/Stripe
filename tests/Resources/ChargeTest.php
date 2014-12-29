<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Charge;

use Arcanedev\Stripe\Tests\StripeTestCase;

class ChargeTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Charge */
    private $charge;

    /** @var array */
    private $chargeData = [];

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\Charge';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->charge = new Charge;
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

        unset($this->charge);
        $this->chargeData = [];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->charge);
    }

    /**
     * @test
     */
    public function testCanGetUrl()
    {
        $this->assertEquals(Charge::classUrl(), '/v1/charges');

        $this->charge = new Charge('abcd/efgh');

        $this->assertEquals($this->charge->instanceUrl(), '/v1/charges/abcd%2Fefgh');
    }

    /**
     * @test
     */
    public function testCanCreate()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->charge);
        $this->assertTrue($this->charge->paid);
        $this->assertFalse($this->charge->refunded);
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
        $charge       = Charge::create($this->chargeData);
        $this->charge = Charge::retrieve($charge->id);

        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->charge);
        $this->assertEquals($charge->id, $this->charge->id);
    }

    /**
     * @test
     */
    public function testCanGetAll()
    {
        $charges = Charge::all();

        $this->assertTrue($charges->isList());
        $this->assertEquals('/v1/charges', $charges->url);
    }

    /**
     * @test
     */
    public function testCanRefundTotalAmount()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->refund();
        $this->assertTrue($this->charge->refunded);
        $this->assertEquals(1, count($this->charge->refunds->data));

        $refund = $this->charge->refunds->data[0];
        $this->assertEquals('refund', $refund->object);
        $this->assertEquals(100, $refund->amount);
    }

    /**
     * @test
     */
    public function testCanRefundPartialAmount()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->refund(['amount' => 50,]);
        $this->assertFalse($this->charge->refunded);
        $this->assertEquals(1, count($this->charge->refunds->data));

        $this->charge->refund(['amount' => 50,]);
        $this->assertTrue($this->charge->refunded);
        $this->assertEquals(2, count($this->charge->refunds->data));
    }

    /**
     * @test
     */
    public function testCanCapture()
    {
        $this->charge = Charge::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'card'      => [
                'number'    => '4242424242424242',
                'exp_month' => 5,
                'exp_year'  => 2015
            ],
            'capture'  => false
        ]);

        $this->assertFalse($this->charge->captured);
        $this->charge->capture();
        $this->assertTrue($this->charge->captured);
    }

    public function testCanUpdateDispute()
    {
        // TODO: Complete testCanUpdateDispute() implementation
    }

    public function testCanCloseDispute()
    {
        // TODO: Complete testCanCloseDispute() implementation
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Metadata Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanUpdateOneMetadata()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->metadata['test'] = 'foo bar';
        $this->charge->save();

        $charge = Charge::retrieve($this->charge->id);
        $this->assertEquals('foo bar', $charge->metadata['test']);
    }

    /**
     * @test
     */
    public function testCanUpdateAllMetadata()
    {
        $this->charge = Charge::create($this->chargeData);

        $this->charge->metadata = ['test' => 'foo bar'];
        $this->charge->save();

        $charge = Charge::retrieve($this->charge->id);
        $this->assertEquals('foo bar', $charge->metadata['test']);
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
