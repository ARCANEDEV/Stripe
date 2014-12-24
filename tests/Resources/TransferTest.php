<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Transfer;

use Arcanedev\Stripe\Tests\StripeTestCase;

class TransferTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanCreate()
    {
        $recipient = self::createTestRecipient();

        $transfer = Transfer::create([
            'amount' => 100,
            'currency' => 'usd',
            'recipient' => $recipient->id
        ]);

        $this->assertEquals('pending', $transfer->status);
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $recipient = self::createTestRecipient();

        $transfer = Transfer::create([
            'amount' => 100,
            'currency' => 'usd',
            'recipient' => $recipient->id
        ]);

        $reloaded = Transfer::retrieve($transfer->id);
        $this->assertEquals($reloaded->id, $transfer->id);
    }

    /**
     * @test
     */
    public function testCanCancel()
    {
        $recipient = self::createTestRecipient();

        $transfer = Transfer::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'recipient' => $recipient->id
        ]);

        $reloaded = Transfer::retrieve($transfer->id);
        $this->assertEquals($reloaded->id, $transfer->id);

        $reloaded->cancel();
    }

    /**
     * @test
     */
    public function testCanUpdateOneMetadata()
    {
        $recipient = self::createTestRecipient();

        $transfer = Transfer::create([
            'amount' => 100,
            'currency' => 'usd',
            'recipient' => $recipient->id
        ]);

        $transfer->metadata['test'] = 'foo bar';
        $transfer->save();

        $updatedTransfer = Transfer::retrieve($transfer->id);
        $this->assertEquals('foo bar', $updatedTransfer->metadata['test']);
    }

    /**
     * @test
     */
    public function testCanUpdateAllMetadata()
    {
        $recipient = self::createTestRecipient();

        $transfer = Transfer::create([
            'amount'    => 100,
            'currency'  => 'usd',
            'recipient' => $recipient->id
        ]);

        $transfer->metadata = ['test' => 'foo bar'];
        $transfer->save();

        $updatedTransfer = Transfer::retrieve($transfer->id);
        $this->assertEquals('foo bar', $updatedTransfer->metadata['test']);
    }
}
