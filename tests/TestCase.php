<?php namespace Arcanedev\Stripe\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase
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
     |  Common Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate a random 8-character string. Useful for ensuring
     * multiple test suite runs don't conflict
     */
    protected static function randomString()
    {
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $str = "";
        for ($i = 0; $i < 10; $i++) {
            $str .= $chars[ rand(0, strlen($chars) - 1) ];
        }

        return $str;
    }
}
