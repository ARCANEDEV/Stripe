<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTestCase;

class TokenTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const TOKEN_CLASS = 'Arcanedev\\Stripe\\Resources\\Token';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Token */
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
        parent::tearDown();

        unset($this->token);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::TOKEN_CLASS, $this->token);
    }

    /** @test */
    public function it_can_get_urls()
    {
        $this->assertEquals('/v1/tokens', Token::classUrl());

        $this->assertEquals('/v1/tokens/abcd%2Fefgh', $this->token->instanceUrl());
    }

    /** @test */
    public function it_can_create()
    {
        $this->token = self::createToken();

        $this->assertInstanceOf(self::TOKEN_CLASS, $this->token);
        $this->assertEquals('card', $this->token->type);
        $this->assertInstanceOf(
            'Arcanedev\Stripe\Resources\Card',
            $this->token->card
        );
        $this->assertEquals('Visa', $this->token->card->brand);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $token       = self::createToken();
        $this->token = Token::retrieve($token->id);

        $this->assertEquals($token->id, $this->token->id);
        $this->assertEquals($token->type, $this->token->type);
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
    private function createToken()
    {
        return Token::create([
            "card" => [
                "number"    => "4242424242424242",
                "exp_month" => 12,
                "exp_year"  => 2015,
                "cvc"       => "314"
            ]
        ]);
    }
}
