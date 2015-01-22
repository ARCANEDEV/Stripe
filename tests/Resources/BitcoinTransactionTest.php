<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Resources\BitcoinTransaction;
use Arcanedev\Stripe\Tests\StripeTestCase;

class BitcoinTransactionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var BitcoinTransaction */
    protected $object;

    const RESOURCE_CLASS = 'Arcanedev\\Stripe\\Resources\\BitcoinTransaction';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->object = new BitcoinTransaction;
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
        $this->assertInstanceOf(self::RESOURCE_CLASS, $this->object);
    }

    /**
     * @test
     */
    public function testCanGetUrls()
    {
        $this->assertEquals(
            '/v1/bitcoin/transactions',
            BitcoinTransaction::classUrl()
        );

        $instanceUrl = (new BitcoinTransaction('abcd/efgh'))
            ->instanceUrl();
        $this->assertEquals($instanceUrl, '/v1/bitcoin/transactions/abcd%2Fefgh');
    }
}
