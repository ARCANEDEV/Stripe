<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Transfer;

use Arcanedev\Stripe\Tests\StripeTestCase;

class TransferTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Transfer */
    private $transfer;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\Transfer';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->transfer = new Transfer;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->transfer);
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
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->transfer);
    }

    /**
     * @test
     */
    public function testCanGetAll()
    {
        $transfers = Transfer::all();

        $this->assertTrue($transfers->isList());
        $this->assertEquals('/v1/transfers', $transfers->url);
    }

    /**
     * @test
     */
    public function testCanCreate()
    {
        $recipient = self::createTestRecipient();

        $this->transfer = Transfer::create([
            'amount' => 100,
            'currency' => 'usd',
            'recipient' => $recipient->id
        ]);

        $this->assertEquals('pending', $this->transfer->status);
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $recipient = self::createTestRecipient();

        $this->transfer = Transfer::create([
            'amount' => 100,
            'currency' => 'usd',
            'recipient' => $recipient->id
        ]);

        $transfer = Transfer::retrieve($this->transfer->id);

        $this->assertEquals($transfer->id, $this->transfer->id);
    }

    /**
     * @test
     */
    public function testCanCancel()
    {
        $recipient = self::createTestRecipient();

        $this->transfer = Transfer::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'recipient' => $recipient->id
        ]);

        $transfer = Transfer::retrieve($this->transfer->id);
        $this->assertEquals($transfer->id, $this->transfer->id);

        $transfer->cancel();
    }

    /**
     * @test
     */
    public function testCanUpdateOneMetadata()
    {
        $recipient = self::createTestRecipient();

        $this->transfer = Transfer::create([
            'amount' => 100,
            'currency' => 'usd',
            'recipient' => $recipient->id
        ]);

        $this->transfer->metadata['test'] = 'foo bar';
        $this->transfer->save();

        $transfer = Transfer::retrieve($this->transfer->id);

        $this->assertEquals('foo bar', $transfer->metadata['test']);
    }

    /**
     * @test
     */
    public function testCanUpdateAllMetadata()
    {
        $recipient = self::createTestRecipient();

        $this->transfer = Transfer::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'recipient' => $recipient->id
        ]);

        $this->transfer->metadata = ['test' => 'foo bar'];
        $this->transfer->save();

        $transfer = Transfer::retrieve($this->transfer->id);
        $this->assertEquals('foo bar', $transfer->metadata['test']);
    }
}
