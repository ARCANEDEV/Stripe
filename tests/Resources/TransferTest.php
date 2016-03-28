<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Transfer;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     TransferTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransferTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Resources\Transfer */
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
        unset($this->transfer);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Transfer', $this->transfer);
    }

    /** @test */
    public function it_can_list_all()
    {
        $transfers = Transfer::all();

        $this->assertTrue($transfers->isList());
        $this->assertEquals('/v1/transfers', $transfers->url);
    }

    /** @test */
    public function it_can_create()
    {
        $this->transfer = self::createTestTransfer();

        $this->assertEquals('pending', $this->transfer->status);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $this->transfer = self::createTestTransfer();
        $retrievedTrans = Transfer::retrieve($this->transfer->id);

        $this->assertEquals($this->transfer->id, $retrievedTrans->id);
    }

    /** @test */
    public function it_can_cancel()
    {
        $this->transfer = self::createTestTransfer();
        $retrievedTrans = Transfer::retrieve($this->transfer->id);

        $this->assertEquals($this->transfer->id, $retrievedTrans->id);

        if ($retrievedTrans->status !== 'paid')
            $retrievedTrans->cancel();
    }

    /** @test */
    public function it_can_update_one_metadata()
    {
        $this->transfer = self::createTestTransfer();
        $this->transfer->metadata['test'] = 'foo bar';
        $this->transfer->save();

        $transfer = Transfer::retrieve($this->transfer->id);

        $this->assertEquals('foo bar', $transfer->metadata['test']);
    }

    /** @test */
    public function it_can_update_all_metadata()
    {
        $this->transfer = self::createTestTransfer();
        $this->transfer->metadata = ['test' => 'foo bar'];
        $this->transfer->save();

        $transfer = Transfer::retrieve($this->transfer->id);
        $this->assertEquals('foo bar', $transfer->metadata['test']);
    }
}
