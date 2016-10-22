<?php namespace Arcanedev\Stripe\Tests;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Assertion Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Asserts that a count value is greater than another value.
     *
     * @param  mixed   $expected
     * @param  mixed   $actual
     * @param  string  $message
     */
    protected function assertCountGreaterThan($expected, $actual, $message = '')
    {
        $this->assertGreaterThan($expected, count($actual), $message);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Common Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get private or protected method of a given class
     *
     * @param  string  $class
     * @param  string  $methodName
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
     * @param  int  $length
     *
     * @return string
     */
    protected static function generateRandomString($length = 24)
    {
        $chars       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charsLength = strlen($chars);
        $random      = '';

        for ($i = 0; $i < $length; $i++) {
            $random .= $chars[rand(0, $charsLength - 1)];
        }

        return $random;
    }

    /**
     * Generate a semi-random email.
     *
     * @param  string  $domain
     *
     * @return string
     */
    protected static function generateRandomEmail($domain = 'bar.com')
    {
        return self::generateRandomString().'@'.$domain;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Dump and die.
     */
    protected function dd()
    {
        array_map('var_dump', func_get_args());
        die(1);
    }
}
