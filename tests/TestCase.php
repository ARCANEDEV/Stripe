<?php namespace Arcanedev\Stripe\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
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
     * Generate a semi-random string
     *
     * @param int $length
     *
     * @return string
     */
    protected static function generateRandomString($length = 24)
    {
        $chars       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTU';
        $charsLength = strlen($chars);
        $random      = '';

        for ($i = 0; $i < $length; $i++) {
            $random .= $chars[rand(0, $charsLength - 1)];
        }

        return $random;
    }
}
