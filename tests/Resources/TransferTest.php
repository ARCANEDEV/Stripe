<?php namespace Arcanedev\Stripe\Tests\Resources;


use Arcanedev\Stripe\Resources\Recipient;
use Arcanedev\Stripe\Resources\Transfer;
use Arcanedev\Stripe\Tests\StripeTest;

class TransferTest extends StripeTest
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
    public function testCreate()
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
    public function testRetrieve()
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
    public function testCancel()
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
    public function testTransferUpdateMetadata()
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
    public function testTransferUpdateMetadataAll()
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

    /**
     * @test
     */
    public function testRecipientUpdateMetadata()
    {
        $recipient = self::createTestRecipient();

        $recipient->metadata['test'] = 'foo bar';
        $recipient->save();

        $updatedRecipient = Recipient::retrieve($recipient->id);
        $this->assertEquals('foo bar', $updatedRecipient->metadata['test']);
    }

    /**
     * @test
     */
    public function testRecipientUpdateMetadataAll()
    {
        $recipient = self::createTestRecipient();

        $recipient->metadata = ['test' => 'foo bar'];
        $recipient->save();

        $updatedRecipient = Recipient::retrieve($recipient->id);
        $this->assertEquals('foo bar', $updatedRecipient->metadata['test']);
    }
}
