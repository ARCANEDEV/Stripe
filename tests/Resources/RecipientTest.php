<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Card;
use Arcanedev\Stripe\Resources\Recipient;
use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTest;

class RecipientTest extends StripeTest
{
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
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\Recipient',
            $this->recipient
        );
    }

    public function testDeletion()
    {
        $recipient = self::createTestRecipient();
        $recipient->delete();

        $this->assertTrue($recipient->deleted);
    }

    public function testSave()
    {
        $recipient = self::createTestRecipient();

        $recipient->email = 'gdb@stripe.com';
        $recipient->save();

        $this->assertEquals('gdb@stripe.com', $recipient->email);

        $stripeRecipient = Recipient::retrieve($recipient->id);
        $this->assertEquals($recipient->email, $stripeRecipient->email);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function testBogusAttribute()
    {
        $recipient = self::createTestRecipient();
        $recipient->bogus = 'bogus';
    }

    /**
     * @test
     */
    public function testRecipientAddCard()
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

    /**
     * @test
     */
    public function testRecipientUpdateCard()
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

    /**
     * @test
     */
    public function testRecipientDeleteCard()
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
}
