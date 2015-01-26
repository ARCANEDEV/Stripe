<?php namespace Arcanedev\Stripe\Tests\Utilities\Request;

use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Request\CurlClient;

class CurlClientTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const CURL_CLIENT_CLASS = 'Arcanedev\\Stripe\\Utilities\\Request\\CurlClient';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var CurlClient */
    private $curlClient;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->curlClient = new CurlClient($this->myApiKey, 'https://www.stripe.com');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->curlClient);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::CURL_CLIENT_CLASS, $this->curlClient);
    }

    // TODO: Add more CurlClient Tests
}
