<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinTransaction;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     BitcoinTransactionTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BitcoinTransactionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var BitcoinTransaction */
    protected $object;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->object = new BitcoinTransaction;
    }

    public function tearDown()
    {
        unset($this->object);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\BitcoinTransaction',
            $this->object
        );
    }

    /** @test */
    public function it_can_get_urls()
    {
        $this->assertEquals(
            '/v1/bitcoin/transactions',
            BitcoinTransaction::classUrl()
        );

        $instanceUrl = (new BitcoinTransaction('abcd/efgh'))->instanceUrl();
        $this->assertEquals('/v1/bitcoin/transactions/abcd%2Fefgh', $instanceUrl);
    }
}
