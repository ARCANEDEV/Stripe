<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Tests\StripeTestCase;
use Arcanedev\Stripe\Utilities\Util;

class UtilTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanConvertStripeObjectToArray()
    {
        $arrayToConvert = [
            '_'         => 'Hello',
            'customer'  => new Customer([
                "id"    => 'cust_kjfhdflsdhfdsjl',
            ])
        ];

        $array = Util::convertStripeObjectToArray($arrayToConvert);

        $this->assertEquals([
            'customer' => [
                "id"    => 'cust_kjfhdflsdhfdsjl',
            ]
        ], $array);
    }
}
