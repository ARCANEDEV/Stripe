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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Stripe\Resources\Transfer */
    private $transfer;

    /**
     * The resource that was traditionally called "transfer" became a "payout" in API version 2017-04-06.
     * We're testing traditional transfers here, so we force the API version just prior anywhere that we need to.
     *
     * @var array
     */
    protected $options = ['stripe_version' => '2017-02-14'];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Transfer::class, $this->transfer);
    }

    /** @test */
    public function it_can_get_all()
    {
        $transfers = Transfer::all();

        $this->assertTrue($transfers->isList());
        $this->assertSame('/v1/transfers', $transfers->url);
    }

    /** @test */
    public function it_can_create()
    {
        $this->transfer = self::createTestTransfer([], $this->options);

        $this->assertSame('pending', $this->transfer->status);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $transfer = self::createTestTransfer([], $this->options);
        $reloaded = Transfer::retrieve($transfer->id, $this->options);

        $this->assertSame($reloaded->id, $transfer->id);
    }

    /** @test */
    public function it_can_update_metadata()
    {
        $transfer = self::createTestTransfer([], $this->options);

        $transfer->metadata['test'] = 'foo bar';
        $transfer->save();

        $updatedTransfer = Transfer::retrieve($transfer->id, $this->options);

        $this->assertSame('foo bar', $updatedTransfer->metadata['test']);
    }

    /** @test */
    public function it_can_update_all_metadata()
    {
        $transfer = self::createTestTransfer([], $this->options);

        $transfer->metadata = ['test' => 'foo bar'];
        $transfer->save();

        $updatedTransfer = Transfer::retrieve($transfer->id, $this->options);

        $this->assertSame('foo bar', $updatedTransfer->metadata['test']);
    }
}
