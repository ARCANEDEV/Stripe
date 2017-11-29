<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Card;
use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     TokenTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TokenTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Resources\Token */
    private $token;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->token = new Token('abcd/efgh');
    }

    public function tearDown()
    {
        unset($this->token);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Token::class, $this->token);
    }

    /** @test */
    public function it_can_get_urls()
    {
        $this->assertSame('/v1/tokens',             Token::classUrl());
        $this->assertSame('/v1/tokens/abcd%2Fefgh', $this->token->instanceUrl());
    }

    /** @test */
    public function it_can_create_card_token()
    {
        $this->token = self::createCardToken();

        $this->assertInstanceOf(Token::class, $this->token);
        $this->assertSame('card', $this->token->type);

        $this->assertInstanceOf(Card::class, $this->token->card);
        $this->assertSame('Visa', $this->token->card->brand);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $token       = self::createCardToken();
        $this->token = Token::retrieve($token->id);

        $this->assertSame($token->id,   $this->token->id);
        $this->assertSame($token->type, $this->token->type);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a card Token for tests
     *
     * @return Token
     */
    private function createCardToken()
    {
        return Token::create([
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => date('n'),
                'exp_year'  => date('Y') + 1,
                'cvc'       => '314',
            ]
        ]);
    }
}
