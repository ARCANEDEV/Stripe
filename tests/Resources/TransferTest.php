<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Transfer;
use Arcanedev\Stripe\Tests\StripeTestCase;

class TransferTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const TRANSFER_CLASS = 'Arcanedev\\Stripe\\Resources\\Transfer';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Transfer */
    private $transfer;

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
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::TRANSFER_CLASS, $this->transfer);
    }

    /**
     * @test
     */
    public function it_can_list_all()
    {
        $transfers = Transfer::all();

        $this->assertTrue($transfers->isList());
        $this->assertEquals('/v1/transfers', $transfers->url);
    }

    /**
     * @test
     */
    public function it_can_create()
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
    public function it_can_retrieve()
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
    public function it_can_cancel()
    {
        $recipient = self::createTestRecipient();

        $this->transfer = Transfer::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'recipient' => $recipient->id
        ]);

        $transfer = Transfer::retrieve($this->transfer->id);
        $this->assertEquals($transfer->id, $this->transfer->id);

        if ($transfer->status !== 'paid') {
            $transfer->cancel();
        }
    }

    /**
     * @test
     */
    public function it_can_update_one_metadata()
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
    public function it_can_update_all_metadata()
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
