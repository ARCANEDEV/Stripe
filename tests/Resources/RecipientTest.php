<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Card;
use Arcanedev\Stripe\Resources\Recipient;
use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTestCase;

class RecipientTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RECIPIENT_CLASS = 'Arcanedev\\Stripe\\Resources\\Recipient';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Recipient */
    private $recipient;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->recipient = new Recipient;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->recipient);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::RECIPIENT_CLASS, $this->recipient);
    }

    /** @test */
    public function it_can_list_all()
    {
        $recipients = Recipient::all();

        $this->assertTrue($recipients->isList());
        $this->assertEquals('/v1/recipients', $recipients->url);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $this->recipient = self::createTestRecipient();

        $recipient = Recipient::retrieve($this->recipient->id);

        $this->assertInstanceOf(self::RECIPIENT_CLASS, $recipient);

        $this->assertEquals($this->recipient->id, $this->recipient->id);
        $this->assertEquals($this->recipient->name, $this->recipient->name);
    }

    /** @test */
    public function it_can_save()
    {
        $recipient = self::createTestRecipient();

        $recipient->email = 'gdb@stripe.com';
        $recipient->save();

        $this->assertEquals('gdb@stripe.com', $recipient->email);

        $stripeRecipient = Recipient::retrieve($recipient->id);
        $this->assertEquals($recipient->email, $stripeRecipient->email);
    }

    /** @test */
    public function it_can_delete()
    {
        $recipient = self::createTestRecipient();
        $recipient->delete();

        $this->assertTrue($recipient->deleted);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function it_must_throw_invalid_argument_exception_on_bogus_attribute()
    {
        $recipient        = self::createTestRecipient();
        $recipient->bogus = 'bogus';
    }

    /** @test */
    public function it_can_update_all_metadata_recipient()
    {
        $recipient = self::createTestRecipient();

        $recipient->metadata = ['test' => 'foo bar'];
        $recipient->save();

        $updatedRecipient = Recipient::retrieve($recipient->id);
        $this->assertEquals('foo bar', $updatedRecipient->metadata['test']);
    }

    /** @test */
    public function it_can_update_one_metadata_recipient()
    {
        $recipient = self::createTestRecipient();

        $recipient->metadata['test'] = 'foo bar';
        $recipient->save();

        $updatedRecipient = Recipient::retrieve($recipient->id);
        $this->assertEquals('foo bar', $updatedRecipient->metadata['test']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Cards Tests
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_add_card_to_recipient()
    {
        $token = Token::create([
            "card" => [
                "number"    => "4000056655665556",
                "exp_month" => 5,
                "exp_year"  => date('Y') + 3,
                "cvc"       => "314"
            ]
        ]);

        $recipient = $this->createTestRecipient();
        $recipient->cards->create([
            "card" => $token->id
        ]);
        $recipient->save();

        $updatedRecipient   = Recipient::retrieve($recipient->id);
        $updatedCards       = $updatedRecipient->cards->all();
        $this->assertEquals(1, count($updatedCards["data"]));

    }

    /** @test */
    public function it_can_update_recipient_card()
    {
        $token = Token::create([
            "card" => [
                "number"    => "4000056655665556",
                "exp_month" => 5,
                "exp_year"  => date('Y') + 3,
                "cvc"       => "314"
            ]
        ]);

        $recipient = $this->createTestRecipient();
        $recipient->cards->create([
            "card" => $token->id
        ]);
        $recipient->save();

        $createdCards = $recipient->cards->all();
        $this->assertEquals(1, count($createdCards["data"]));

        /** @var Card $card */
        $card       = $createdCards['data'][0];
        $card->name = "Jane Austen";
        $card->save();

        $updatedRecipient   = Recipient::retrieve($recipient->id);
        $updatedCards       = $updatedRecipient->cards->all();
        $this->assertEquals("Jane Austen", $updatedCards["data"][0]->name);
    }

    /** @test */
    public function it_can_delete_recipient_card()
    {
        $token = Token::create([
            "card" => [
                "number"    => "4000056655665556",
                "exp_month" => 5,
                "exp_year"  => date('Y') + 3,
                "cvc"       => "314"
            ]
        ]);

        $recipient      = $this->createTestRecipient();
        $createdCard    = $recipient->cards->create(["card" => $token->id]);
        $recipient->save();

        $updatedRecipient   = Recipient::retrieve($recipient->id);
        $updatedCards       = $updatedRecipient->cards->all();
        $this->assertEquals(1, count($updatedCards["data"]));

        $deleteStatus = $updatedRecipient->cards->retrieve($createdCard->id)->delete();
        $this->assertTrue($deleteStatus->deleted);
        $updatedRecipient->save();

        $postDeleteRecipient    = Recipient::retrieve($recipient->id);
        $postDeleteCards        = $postDeleteRecipient->cards->all();
        $this->assertEquals(0, count($postDeleteCards["data"]));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Transfer Tests
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_list_all_recipient_transfers()
    {
        $this->recipient = self::createTestRecipient();
        $transfers       = $this->recipient->transfers();

        $this->assertTrue($transfers->isList());
        $this->assertEquals('/v1/transfers', $transfers->url);
    }
}
