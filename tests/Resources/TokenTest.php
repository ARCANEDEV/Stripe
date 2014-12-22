<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Token;
use Arcanedev\Stripe\Tests\StripeTest;

class TokenTest extends StripeTest
{
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
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Token', $this->token);
    }

    /**
     * @test
     */
    public function testCanGetUrls()
    {
        $this->assertEquals('/v1/tokens', Token::classUrl());

        $this->assertEquals('/v1/tokens/abcd%2Fefgh', $this->token->instanceUrl());
    }
}
