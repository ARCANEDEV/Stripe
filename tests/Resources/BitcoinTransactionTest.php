<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinTransaction;
use Arcanedev\Stripe\Tests\StripeTestCase;

class BitcoinTransactionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\BitcoinTransaction';

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
        parent::tearDown();

        unset($this->object);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->object);
    }

    /** @test */
    public function it_can_get_urls()
    {
        $this->assertEquals(
            '/v1/bitcoin/transactions',
            BitcoinTransaction::classUrl()
        );

        $instanceUrl = (new BitcoinTransaction('abcd/efgh'))->instanceUrl();
        $this->assertEquals($instanceUrl, '/v1/bitcoin/transactions/abcd%2Fefgh');
    }
}
