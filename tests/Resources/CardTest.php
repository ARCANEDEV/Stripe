<?php namespace Arcanedev\Stripe\Tests\Resources;


use Arcanedev\Stripe\Resources\Card;
use Arcanedev\Stripe\Tests\StripeTestCase;

class CardTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Card */
    private $card;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->card = new Card;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->card);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCaBeInstantiated()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Card', $this->card);
    }

    /**
     * @test
     */
    public function testCanGetInstanceUrl()
    {
        $cardId      = 'card_random_id';
        $customerId  = 'customer_random_id';
        $recipientId = 'recipient_random_id';

        $this->card->id = $cardId;
        $this->assertNull($this->card->instanceUrl());

        $this->card->customer = $customerId;
        $this->assertEquals(
            "/v1/customers/$customerId/cards/$cardId",
            $this->card->instanceUrl()
        );
        unset($this->card->customer);

        $this->card->recipient = $recipientId;
        $this->assertEquals(
            "/v1/recipients/$recipientId/cards/$cardId",
            $this->card->instanceUrl()
        );
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    public function testMustThrowInvalidRequestExceptionWhenIdEmpty()
    {
        $this->card->instanceUrl();
    }
}
