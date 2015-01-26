<?php namespace Arcanedev\Stripe\Tests;

use ReflectionClass;

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
     * Get private or protected method of a given class
     *
     * @param        $class
     * @param string $methodName
     *
     * @return \ReflectionMethod
     */
    protected static function getMethod($class, $methodName)
    {
        $methodName = (new ReflectionClass($class))
            ->getMethod($methodName);
        $methodName->setAccessible(true);

        return $methodName;
    }

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
