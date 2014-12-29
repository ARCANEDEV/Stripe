<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Tests\Dummies\ResourceObject;

class ResourceTest extends StripeTestCase
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
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\InvalidArgumentException
     */
    public function testMustThrowInvalidArgumentExceptionOnParams()
    {
        ResourceObject::all('parameters');
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function testMustThrowApiExceptionOnApiKey()
    {
        ResourceObject::all(['param' => 'value'], true);
    }
}
